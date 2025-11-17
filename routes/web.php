<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\CommunityController;
use App\Livewire\Leaderboard;
use App\Livewire\UserProfile\Create as UserProfileCreate;
use App\Http\Controllers\Web\UserController as WebUserController;


use App\Livewire\Habits\Habitcreate;
use App\Livewire\Habits\HabitShow;
use Illuminate\Support\Facades\Route;
use App\Livewire\Habits\HabitList;
use App\Livewire\Admin\Users\UserList;
use App\Livewire\UsersProfile\Create as UsersProfileCreate;
use App\Models\Habit;
use App\Livewire\Challenges\ChallengeList;
use App\Livewire\Challenges\ChallengeDetail;
use App\Livewire\Challenges\ChallengesByLocation;
use App\Livewire\Challenges\CreateChallenge;
use App\Livewire\Challenges\MyChallengeList;
use App\Livewire\Challenges\EditChallenge;

Route::view('/', 'welcome')->name('homepage');

// Public pages
Route::get('/challenges', ChallengeList::class)->name('challenges.index');

Route::get('/challenges/location', ChallengesByLocation::class)
    ->name('challenges.by-location')
    ->middleware('auth');

Route::get('/challenges/{challenge}', ChallengeDetail::class)->name('challenges.show');

Route::get('leaderboard', Leaderboard::class)->name('leaderboard');


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

Route::middleware(['auth'])->group(function () {
    //Route::get('/users', UserProfileCreate::class)->name('users.index');
    Route::get('/users', [WebUserController::class, 'index'])->name('users.index');
    //Route::post('/users/{id}/follow', [WebUserController::class, 'follow'])->name('users.follow');
    //Route::post('/users/{id}/unfollow', [WebUserController::class, 'unfollow'])->name('users.unfollow');
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
