<?php

namespace App\Services;

use App\Models\User;

class LeagueService
{
    public const LEAGUES = [
        'bronze' => ['name' => 'Bronze', 'min' => 0, 'color' => 'text-orange-600', 'bg' => 'bg-orange-100', 'border' => 'border-orange-300', 'icon' => 'bg-orange-500'],
        'silver' => ['name' => 'Silver', 'min' => 500, 'color' => 'text-gray-500', 'bg' => 'bg-gray-100', 'border' => 'border-gray-300', 'icon' => 'bg-gray-400'],
        'gold' => ['name' => 'Gold', 'min' => 2000, 'color' => 'text-amber-600', 'bg' => 'bg-amber-100', 'border' => 'border-amber-300', 'icon' => 'bg-amber-500'],
        'diamond' => ['name' => 'Diamond', 'min' => 5000, 'color' => 'text-blue-600', 'bg' => 'bg-blue-100', 'border' => 'border-blue-300', 'icon' => 'bg-blue-500'],
    ];

    public function getLeagueForXp(int $xp): array
    {
        $current = 'bronze';
        foreach (self::LEAGUES as $key => $league) {
            if ($xp >= $league['min']) {
                $current = $key;
            }
        }

        return self::LEAGUES[$current] + ['key' => $current];
    }

    public function getLeagueForUser(User $user): array
    {
        return $this->getLeagueForXp($user->xp_total);
    }

    public function getPromotionZone(int $totalInLeague): int
    {
        return max(1, intval(ceil($totalInLeague * 0.2))); // top 20% get promoted
    }
}
