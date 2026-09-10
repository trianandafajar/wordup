<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LeagueService;
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
                    'id' => $user->id,
                ];
            });

        $totalInLeague = $leagueUsers->count();
        $promotionCount = $leagueService->getPromotionZone($totalInLeague);

        $currentUserRecord = $leagueUsers->firstWhere('id', $currentUser->id);
        $currentUserLeagueRank = $currentUserRecord['rank'] ?? 1;

        return view('livewire.user.leaderboard', [
            'leagueUsers' => $leagueUsers,
            'currentUser' => $currentUser,
            'currentUserLeague' => $currentUserLeague,
            'currentUserLeagueRank' => $currentUserLeagueRank,
            'promotionCount' => $promotionCount,
            'totalInLeague' => $totalInLeague,
        ]);
    }
}
