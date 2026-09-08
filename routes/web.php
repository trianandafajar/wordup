<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('livewire.user.home');
})->name('user.home');

Route::get('/learn', function () {
    return view('livewire.user.skill-tree');
})->name('user.learn');

Route::get('/onboarding', function () {
    return view('livewire.user.onboarding');
})->name('user.onboarding');

Route::get('/lesson/practice', function () {
    return view('livewire.user.lesson-practice');
})->name('user.lesson.practice');

Route::get('/leaderboard', function () {
    return view('livewire.user.leaderboard');
})->name('user.leaderboard');

Route::get('/profile', function () {
    return view('livewire.user.profile');
})->name('user.profile');
