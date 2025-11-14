<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\CommunityController;
use App\Livewire\UserProfile\Create as UserProfileCreate;
use App\Http\Controllers\Web\UserController as WebUserController;


use App\Livewire\Habits\Habitcreate;
use App\Livewire\Habits\HabitShow;
use Illuminate\Support\Facades\Route;
use App\Livewire\Habits\HabitList;
use App\Livewire\Admin\Users\UserList;
use App\Livewire\UsersProfile\Create as UsersProfileCreate;
use App\Models\Habit;


Route::view('/', 'welcome')->name('homepage');

// Các route cần xác thực người dùng
Route::middleware('auth')->group(function () {
    Route::get('/habits/create', Habitcreate::class)->name('habits.create');
    Route::get('/habits/{habit}/edit', \App\Livewire\Habits\HabitEdit::class)->name('habits.edit');
});

Route::middleware(['auth'])->group(function () {
    //Route::get('/users', UserProfileCreate::class)->name('users.index');
    Route::get('/users', [WebUserController::class, 'index'])->name('users.index');

    Route::post('/users/{id}/follow', [WebUserController::class, 'follow'])->name('users.follow');
    Route::post('/users/{id}/unfollow', [WebUserController::class, 'unfollow'])->name('users.unfollow');
    Route::post('/users/{id}/toggle-follow', [WebUserController::class, 'toggleFollow'])->name('users.toggleFollow');

    Route::get('/admin/users', UserList::class)->name('admin.users');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/community', [CommunityController::class, 'index'])->name('community');
});

// Các route công khai hoặc có logic kiểm tra quyền riêng
Route::get('/habits', HabitList::class)->name('habits.index');
Route::get('/habits/{habit}', HabitShow::class)->name('habits.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
