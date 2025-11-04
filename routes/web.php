<?php

use App\Livewire\Habits\Habitcreate;
use App\Livewire\Habits\HabitShow;
use Illuminate\Support\Facades\Route;
use App\Livewire\Habits\HabitList;
use App\Models\Habit;
Route::view('/', 'welcome')->name('homepage');

// Route::view('habits', 'habits')->name('habits');
// Route::view('challenges', 'challenges')->name('challenges');
Route::middleware('auth')->group(function () {
    // Các route cụ thể hơn (như 'create', 'edit') nên được định nghĩa trước
    Route::get('/habits/create', Habitcreate::class)->name('habits.create');
    Route::get('/habits/{habit}/edit', \App\Livewire\Habits\HabitEdit::class)->name('habits.edit');
});
Route::get('/habits', HabitList::class)->name('habits.index');
Route::get('/habits', \App\Livewire\Habits\HabitList::class)->name('habits.index');
Route::get('/habits/{habit}', HabitShow::class)->name('habits.show'); 
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
