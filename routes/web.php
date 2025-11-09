<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Challenges\ChallengeList;
use App\Livewire\Challenges\ChallengeDetail;
Route::view('/', 'welcome')->name('homepage');

// Route::view('habits', 'habits')->name('habits');
// Route::view('challenges', 'challenges')->name('challenges');
// Public pages
Route::get('/challenges', ChallengeList::class)->name('challenges.index');
Route::get('/challenges/{id}', ChallengeDetail::class)->name('challenges.show');

// Protected (requires login)
// Route::middleware('auth')->group(function () {
//     Route::get('/challenges/create', ChallengeCreate::class)->name('challenges.create');
// });

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
