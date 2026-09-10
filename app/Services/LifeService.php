<?php

namespace App\Services;

use App\Models\User;

class LifeService
{
    public const MAX_LIVES = 5;

    public const REFILL_INTERVAL_HOURS = 4;

    public function refillIfNeeded(User $user): User
    {
        if ($user->lives >= self::MAX_LIVES) {
            return $user;
        }

        $now = now();
        $lastRefill = $user->last_life_refill_at;

        if (! $lastRefill) {
            $user->update([
                'lives' => self::MAX_LIVES,
                'last_life_refill_at' => $now,
            ]);

            return $user->refresh();
        }

        $elapsedHours = $lastRefill->diffInHours($now);
        $livesToAdd = intdiv($elapsedHours, self::REFILL_INTERVAL_HOURS);

        if ($livesToAdd > 0) {
            $newLives = min(self::MAX_LIVES, $user->lives + $livesToAdd);
            $user->update([
                'lives' => $newLives,
                'last_life_refill_at' => $now,
            ]);
        }

        return $user->refresh();
    }

    public function loseLife(User $user): void
    {
        $user->lives = max(0, $user->lives - 1);
        $user->last_life_refill_at = now();
        $user->save();
    }

    public function secondsUntilNextRefill(User $user): int
    {
        if ($user->lives >= self::MAX_LIVES) {
            return 0;
        }

        if (! $user->last_life_refill_at) {
            return 0;
        }

        $refillSeconds = self::REFILL_INTERVAL_HOURS * 60 * 60;
        $elapsedSeconds = $user->last_life_refill_at->diffInSeconds(now());

        return max(0, $refillSeconds - $elapsedSeconds);
    }
}
