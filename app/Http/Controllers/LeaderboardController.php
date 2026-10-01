<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LeagueService;
use App\Services\RankService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(): View
    {
        $currentUser = Auth::user();
        $leagueService = new LeagueService;

        if (! $currentUser->league) {
            $currentUser->league = $leagueService->getLeagueForXp($currentUser->xp_total)['key'];
            $currentUser->save();
        }

        $currentUserLeague = $leagueService->getLeagueForUser($currentUser);

        $leagueUsers = User::where('league', $currentUserLeague['key'])
            ->orderByDesc('league_week_xp')
            ->orderByDesc('xp_total')
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $user->name,
                    'xp' => $user->league_week_xp > 0 ? $user->league_week_xp : $user->xp_total,
                    'total_xp' => $user->xp_total,
                    'completed_lessons' => $user->courseProgress()->sum('completed_lessons'),
                    'member_since' => $user->created_at->format('M Y'),
                    'id' => $user->id,
                    'avatar' => $user->avatar,
                ];
            });

        $totalInLeague = $leagueUsers->count();
        $promotionCount = $leagueService->getPromotionZone($totalInLeague);

        $currentUserRecord = $leagueUsers->firstWhere('id', $currentUser->id);
        $currentUserLeagueRank = $currentUserRecord['rank'] ?? 1;

        $rankService = new RankService;
        $currentRank = $rankService->getRankForXp($currentUser->xp_total);
        $allRanks = RankService::RANKS;
        $currentRankIndex = $rankService->getRankIndex($currentRank['key']);

        return view('livewire.user.leaderboard', [
            'leagueUsers' => $leagueUsers,
            'currentUser' => $currentUser,
            'currentUserLeague' => $currentUserLeague,
            'currentUserLeagueRank' => $currentUserLeagueRank,
            'promotionCount' => $promotionCount,
            'totalInLeague' => $totalInLeague,
            'currentRank' => $currentRank,
            'allRanks' => $allRanks,
            'currentRankIndex' => $currentRankIndex,
        ]);
    }
}
