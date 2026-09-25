<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\OnboardingController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\SkillTreeController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Middleware\EnsureApiOnboardingCompleted;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'forgot']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/onboarding', [OnboardingController::class, 'index']);
    Route::post('/onboarding', [OnboardingController::class, 'store']);
});

Route::middleware(['auth:sanctum', EnsureApiOnboardingCompleted::class])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/', [HomeController::class, 'index']);
    Route::get('/learn', [SkillTreeController::class, 'index']);
    Route::get('/lesson/{lesson}', [LessonController::class, 'show']);
    Route::post('/lesson/{lesson}/submit', [LessonController::class, 'submit']);
    Route::get('/lesson/{lesson}/result', [LessonController::class, 'result']);
    
    Route::get('/leaderboard', [LeaderboardController::class, 'index']);
    
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
});
