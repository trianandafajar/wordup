<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaderboardResource;
use App\Models\User;
use App\Services\LeagueService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    /**
     * @tags Leaderboard
     *
     * @summary Get leaderboard for current league
     *
     * @return AnonymousResourceCollection
     */
    public function index()
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
                    'avatar' => $user->avatar,
                ];
            });

        return LeaderboardResource::collection($leagueUsers);
    }
}
