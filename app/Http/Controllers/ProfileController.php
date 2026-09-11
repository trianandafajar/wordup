<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $badgeService = new BadgeService;
        $badges = $badgeService->getBadges($user);

        $completedLessonsCount = $user->lessonProgress()->where('status', 'completed')->count();
        $totalXp = $user->xp_total;
        $currentStreak = $user->current_streak;

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
            'badges' => $badges,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name = $request->name;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('user.profile')->with('status', 'Profil berhasil diperbarui.');
    }
}
