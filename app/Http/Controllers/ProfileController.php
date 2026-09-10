<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(): \Illuminate\View\View
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('user.profile')->with('status', 'Profil berhasil diperbarui.');
    }
}
