<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        // Get Top 10 users by XP
        $topUsers = User::orderByDesc('xp_total')
            ->limit(10)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $user->name,
                    'xp' => $user->xp_total,
                    'id' => $user->id,
                ];
            });

        // Find current user rank
        $currentUserRank = User::where('xp_total', '>', $currentUser->xp_total)->count() + 1;

        return view('livewire.user.leaderboard', [
            'topUsers' => $topUsers,
            'currentUser' => $currentUser,
            'currentUserRank' => $currentUserRank,
        ]);
    }
}
