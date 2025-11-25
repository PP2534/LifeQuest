<?php

use App\Livewire\Admin\Users\UserEdit;
use App\Livewire\Admin\Users\UserList;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Admin routes - chỉ truy cập được từ admin domain
$adminHost = parse_url(config('app.admin_url', 'http://admin.localhost'), PHP_URL_HOST) ?: 'admin.localhost';
Route::domain($adminHost)
    ->middleware(['domain', 'web'])
    ->group(function () {
        
        // Trang đăng nhập công khai cho admin
        Route::middleware('guest')->group(function () {
            Volt::route('login', 'pages.auth.admin-login')
                ->name('admin.login');
        });

        // Tất cả các route khác đều cần xác thực và phải là admin
        Route::middleware(['auth', 'isAdmin'])->group(function () {
            Volt::route('/', 'admin.dashboard')->name('admin.home');
            Volt::route('dashboard', 'admin.dashboard')->name('admin.dashboard');
            Route::get('/users', UserList::class)->name('admin.users.list');
            Route::get('/users/{user}/edit', UserEdit::class)->name('admin.users.edit');
        });
    });

