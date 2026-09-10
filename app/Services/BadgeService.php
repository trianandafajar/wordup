<?php

namespace App\Services;

use App\Models\User;

class BadgeService
{
    public function getBadges(User $user): array
    {
        $badges = [];

        $completedCount = $user->lessonProgress()
            ->where('status', 'completed')
            ->count();

        $totalXp = $user->xp_total;
        $longestStreak = $user->longest_streak;
        $totalLessons = $user->lessonAttempts()->count();

        if ($completedCount >= 1) {
            $badges[] = ['icon' => 'book-open', 'color' => 'bg-green-100 text-green-600', 'title' => 'First Lesson', 'desc' => '1 Lesson selesai'];
        }
        if ($completedCount >= 5) {
            $badges[] = ['icon' => 'book-open', 'color' => 'bg-emerald-100 text-emerald-600', 'title' => 'Bookworm', 'desc' => '5 Lesson selesai'];
        }
        if ($completedCount >= 10) {
            $badges[] = ['icon' => 'book-open', 'color' => 'bg-teal-100 text-teal-600', 'title' => 'Knowledge Seeker', 'desc' => '10 Lesson selesai'];
        }
        if ($completedCount >= 20) {
            $badges[] = ['icon' => 'book-open', 'color' => 'bg-cyan-100 text-cyan-600', 'title' => 'Scholar', 'desc' => '20 Lesson selesai'];
        }

        if ($totalXp >= 100) {
            $badges[] = ['icon' => 'sparkles', 'color' => 'bg-amber-100 text-amber-600', 'title' => 'Rising Star', 'desc' => '100+ XP'];
        }
        if ($totalXp >= 500) {
            $badges[] = ['icon' => 'sparkles', 'color' => 'bg-orange-100 text-orange-600', 'title' => 'XP Hunter', 'desc' => '500+ XP'];
        }
        if ($totalXp >= 1000) {
            $badges[] = ['icon' => 'sparkles', 'color' => 'bg-yellow-100 text-yellow-600', 'title' => 'XP Master', 'desc' => '1.000+ XP'];
        }
        if ($totalXp >= 5000) {
            $badges[] = ['icon' => 'sparkles', 'color' => 'bg-red-100 text-red-600', 'title' => 'XP Legend', 'desc' => '5.000+ XP'];
        }

        if ($longestStreak >= 3) {
            $badges[] = ['icon' => 'fire', 'color' => 'bg-orange-100 text-orange-600', 'title' => 'On Fire', 'desc' => 'Streak 3 hari'];
        }
        if ($longestStreak >= 7) {
            $badges[] = ['icon' => 'fire', 'color' => 'bg-red-100 text-red-600', 'title' => 'Weekly Warrior', 'desc' => 'Streak 7 hari'];
        }
        if ($longestStreak >= 30) {
            $badges[] = ['icon' => 'fire', 'color' => 'bg-rose-100 text-rose-600', 'title' => 'Unstoppable', 'desc' => 'Streak 30 hari'];
        }

        if ($totalLessons >= 10) {
            $badges[] = ['icon' => 'trophy', 'color' => 'bg-blue-100 text-blue-600', 'title' => 'Top 10', 'desc' => '10 percobaan lesson'];
        }

        if ($totalXp < 500) {
            $badges[] = ['icon' => 'user', 'color' => 'bg-gray-100 text-gray-600', 'title' => 'Beginner', 'desc' => 'Level pemula'];
        } elseif ($totalXp < 2000) {
            $badges[] = ['icon' => 'user', 'color' => 'bg-brand-100 text-brand-600', 'title' => 'Intermediate', 'desc' => 'Level menengah'];
        } elseif ($totalXp < 5000) {
            $badges[] = ['icon' => 'user', 'color' => 'bg-indigo-100 text-indigo-600', 'title' => 'Advanced', 'desc' => 'Level lanjutan'];
        } else {
            $badges[] = ['icon' => 'user', 'color' => 'bg-purple-100 text-purple-600', 'title' => 'Fluent', 'desc' => 'Level mahir'];
        }

        return $badges;
    }
}
