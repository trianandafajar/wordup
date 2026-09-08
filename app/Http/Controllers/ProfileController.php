<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get some achievements counts
        $completedLessonsCount = $user->lessonProgress()->where('status', 'completed')->count();
        $totalXp = $user->xp_total;
        $currentStreak = $user->current_streak;

        // Recently completed lessons
        $recentActivities = $user->lessonProgress()
            ->with('lesson')
            ->orderByDesc('completed_at')
            ->limit(3)
            ->get();

        return view('livewire.user.profile', [
            'user' => $user,
            'completedLessonsCount' => $completedLessonsCount,
            'totalXp' => $totalXp,
            'currentStreak' => $currentStreak,
            'recentActivities' => $recentActivities,
        ]);
    }
}
