<?php

use App\Http\Controllers\Api\UserLessonController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillTreeController;
use App\Models\UserReward;
use App\Services\LifeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('login/google', [AuthenticatedSessionController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [AuthenticatedSessionController::class, 'handleGoogleCallback']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware(['auth', 'onboarding'])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/reward/claim', function (Request $request) {
        $request->validate(['unit_id' => 'required', 'level_milestone' => 'required']);
        $reward = UserReward::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'unit_id' => $request->unit_id,
                'level_milestone' => $request->level_milestone,
            ],
            ['is_opened' => false]
        );

        if (! $reward->is_opened) {
            $reward->update(['is_opened' => true]);
            $user = auth()->user();
            $user->energy = min(LifeService::MAX_ENERGY, $user->energy + 5);
            $user->xp_total += 50;
            $user->save();
        }

        return response()->json(['success' => true]);
    })->name('user.reward.claim');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'create'])->name('user.onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store']);
});

Route::get('/', [HomeController::class, 'index'])->name('user.home')->middleware(['auth', 'onboarding']);
Route::get('/learn', [SkillTreeController::class, 'index'])->name('user.learn')->middleware(['auth', 'onboarding']);
Route::get('/lesson/{lesson}', [LessonController::class, 'show'])->name('user.lesson.practice')->middleware(['auth', 'onboarding']);
Route::post('/lesson/{lesson}/submit', [LessonController::class, 'submit'])->name('user.lesson.submit')->middleware(['auth', 'onboarding']);
Route::get('/lesson/{lesson}/result', [LessonController::class, 'result'])->name('user.lesson.result')->middleware(['auth', 'onboarding']);
Route::get('/api/user/{id}/lessons', [UserLessonController::class, 'show'])->middleware(['auth', 'onboarding']);
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('user.leaderboard')->middleware(['auth', 'onboarding']);
Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile')->middleware(['auth', 'onboarding']);
Route::put('/profile', [ProfileController::class, 'update'])->name('user.profile.update')->middleware(['auth', 'onboarding']);
