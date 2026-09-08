<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LeagueService;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $leagueService = new LeagueService;

        $currentUserLeague = $leagueService->getLeagueForUser($currentUser);

        // All users with their league
        $allUsers = User::orderByDesc('xp_total')->get()->map(function ($user, $index) use ($leagueService) {
            $league = $leagueService->getLeagueForUser($user);

            return [
                'rank' => $index + 1,
                'name' => $user->name,
                'xp' => $user->xp_total,
                'id' => $user->id,
                'league' => $league,
            ];
        });

        // Group by league
        $usersByLeague = $allUsers->groupBy('league.key');

        // Current user rank globally
        $currentUserRank = $allUsers->where('xp', '>', $currentUser->xp_total)->count() + 1;

        // Current user rank within their league
        $leagueUsers = $usersByLeague[$currentUserLeague['key']] ?? collect();
        $currentUserLeagueRank = $leagueUsers->where('xp', '>', $currentUser->xp_total)->count() + 1;

        // Promotion zone
        $totalInLeague = $leagueUsers->count();
        $promotionCount = $leagueService->getPromotionZone($totalInLeague);

        return view('livewire.user.leaderboard', [
            'usersByLeague' => $usersByLeague,
            'currentUser' => $currentUser,
            'currentUserLeague' => $currentUserLeague,
            'currentUserRank' => $currentUserRank,
            'currentUserLeagueRank' => $currentUserLeagueRank,
            'promotionCount' => $promotionCount,
            'totalInLeague' => $totalInLeague,
        ]);
    }
}
