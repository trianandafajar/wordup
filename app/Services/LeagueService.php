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

    public function getLeagueByKey(?string $key): array
    {
        $key = $key ?? 'bronze';
        if (! array_key_exists($key, self::LEAGUES)) {
            $key = 'bronze';
        }

        return self::LEAGUES[$key] + ['key' => $key];
    }

    public function getLeagueForUser(User $user): array
    {
        return $this->getLeagueByKey($user->league);
    }

    public function getPromotionZone(int $totalInLeague): int
    {
        return max(1, intval(ceil($totalInLeague * 0.2)));
    }

    public function nextLeagueKey(string $key): ?string
    {
        $keys = array_keys(self::LEAGUES);
        $idx = array_search($key, $keys, true);

        return ($idx === false || $idx === count($keys) - 1) ? null : $keys[$idx + 1];
    }

    public function previousLeagueKey(string $key): ?string
    {
        $keys = array_keys(self::LEAGUES);
        $idx = array_search($key, $keys, true);

        return ($idx === false || $idx === 0) ? null : $keys[$idx - 1];
    }
}
