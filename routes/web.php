<?php

use App\Livewire\Habits\Habitcreate;
use App\Livewire\Habits\HabitShow;
use Illuminate\Support\Facades\Route;
use App\Livewire\Habits\HabitList;
use App\Models\Habit;
use App\Livewire\Challenges\ChallengeList;
use App\Livewire\Challenges\ChallengeDetail;
use App\Livewire\Challenges\CreateChallenge;
use App\Livewire\Challenges\MyChallengeList;
use App\Livewire\Challenges\EditChallenge;

Route::view('/', 'welcome')->name('homepage');

// Public pages
Route::get('/challenges', ChallengeList::class)->name('challenges.index');
Route::get('/challenges/{challenge}', ChallengeDetail::class)->name('challenges.show');

// Protected (requires login)
// Route::middleware('auth')->group(function () {
//     Route::get('/challenges/create', ChallengeCreate::class)->name('challenges.create');
// });

//  route này để truy cập trang tạo
Route::get('/challenge/create', CreateChallenge::class)
    ->middleware('auth') // Chỉ người đã đăng nhập mới được tạo
    ->name('challenges.create');
 
// Route SỬA (Trỏ đến EditChallenge)
Route::get('/challenges/{challenge}/edit', EditChallenge::class) 
    ->middleware('auth')
    ->name('challenges.edit');  

Route::get('/my-challenges', MyChallengeList::class)
    ->middleware('auth')
    ->name('my-challenges');

// Các route cần xác thực người dùng
Route::middleware('auth')->group(function () {
    Route::get('/habits/create', Habitcreate::class)->name('habits.create');
    Route::get('/habits/{habit}/edit', \App\Livewire\Habits\HabitEdit::class)->name('habits.edit');
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
