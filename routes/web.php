<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('homepage');

// Route::view('habits', 'habits')->name('habits');
// Route::view('challenges', 'challenges')->name('challenges');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
