<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LeagueService;
use Illuminate\Console\Command;

class LeagueReset extends Command
{
    protected $signature = 'league:reset';

    protected $description = 'Reset league cycles: promote top 20% of each league, reset weekly XP';

    public function handle(): int
    {
        $leagueService = new LeagueService;

        // Assign initial league for users without one
        User::query()->whereNull('league')->each(function (User $user) use ($leagueService) {
            $league = $leagueService->getLeagueForXp($user->xp_total);
            $user->update([
                'league' => $league['key'],
                'league_week_started_at' => now(),
            ]);
        });

        foreach (array_keys(LeagueService::LEAGUES) as $leagueKey) {
            $users = User::where('league', $leagueKey)
                ->orderByDesc('league_week_xp')
                ->orderByDesc('xp_total')
                ->get();

            if ($users->count() <= 1) {
                continue;
            }

            $promotionZone = $leagueService->getPromotionZone($users->count());
            $totalUsers = $users->count();
            $demotionStart = $totalUsers - $promotionZone;

            foreach ($users as $index => $user) {
                $newLeague = $leagueKey;

                if ($index < $promotionZone) {
                    // Promoted
                    $next = $leagueService->nextLeagueKey($leagueKey);
                    if ($next) {
                        $newLeague = $next;
                    }
                } elseif ($index >= $demotionStart) {
                    // Demoted
                    $prev = $leagueService->previousLeagueKey($leagueKey);
                    if ($prev) {
                        $newLeague = $prev;
                    }
                }

                $user->update([
                    'league' => $newLeague,
                    'league_week_xp' => 0,
                    'league_week_started_at' => now(),
                ]);
            }
        }

        $this->info('League reset completed successfully.');

        return Command::SUCCESS;
    }
}
