<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'create'])->name('user.onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store']);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('/', function () {
    return view('livewire.user.home');
})->name('user.home')->middleware('auth');

Route::get('/learn', function () {
    return view('livewire.user.skill-tree');
})->name('user.learn')->middleware('auth');

Route::get('/lesson/practice', function () {
    return view('livewire.user.lesson-practice');
})->name('user.lesson.practice')->middleware('auth');

Route::get('/leaderboard', function () {
    return view('livewire.user.leaderboard');
})->name('user.leaderboard')->middleware('auth');

Route::get('/profile', function () {
    return view('livewire.user.profile');
})->name('user.profile')->middleware('auth');
