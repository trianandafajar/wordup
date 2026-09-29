<?php

namespace App\Services;

class RankService
{
    public const RANKS = [
        ['key' => 'beginner', 'name' => 'Beginner', 'min_xp' => 0, 'icon' => 'beginner.png'],
        ['key' => 'intermediate', 'name' => 'Intermediate', 'min_xp' => 1000, 'icon' => 'intermediate.png'],
        ['key' => 'advanced', 'name' => 'Advanced', 'min_xp' => 3000, 'icon' => 'advanced.png'],
        ['key' => 'master', 'name' => 'Master', 'min_xp' => 5000, 'icon' => 'master.png'],
    ];

    public function getRankForXp(int $xp): array
    {
        $current = self::RANKS[0];
        foreach (self::RANKS as $rank) {
            if ($xp >= $rank['min_xp']) {
                $current = $rank;
            }
        }

        return $current;
    }

    public function getRankIndex(string $key): int
    {
        foreach (self::RANKS as $index => $rank) {
            if ($rank['key'] === $key) {
                return $index;
            }
        }

        return 0;
    }
}
