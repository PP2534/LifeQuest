<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request for admin.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();
        
        // Kiểm tra nếu user là admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            $adminHost = parse_url(env('ADMIN_URL', 'http://admin.localhost'), PHP_URL_HOST) ?: 'admin.localhost';
            $port = request()->getPort() ?: parse_url(env('APP_URL', 'http://localhost:8000'), PHP_URL_PORT) ?: 8000;
            $scheme = request()->getScheme() ?? 'http';
            $adminUrl = $scheme . '://' . $adminHost . ':' . $port . '/dashboard';
            $this->redirect($adminUrl, navigate: true);
            return;
        }

        // Nếu không phải admin, logout và hiển thị lỗi
        Auth::logout();
        $this->addError('form.email', 'Bạn không có quyền truy cập vào trang quản trị.');
    }
}; ?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-teal-600 mb-2">LifeQuest</h1>
            <h2 class="text-xl font-semibold text-gray-700">Đăng nhập Admin</h2>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email" class="mb-1 font-medium" />
                <x-text-input 
                    wire:model="form.email" 
                    id="email" 
                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400" 
                    type="email" 
                    name="email" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="admin@example.com" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Mật khẩu" class="mb-1 font-medium" />
                <x-text-input 
                    wire:model="form.password" 
                    id="password" 
                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember" class="inline-flex items-center text-sm">
                    <input 
                        wire:model="form.remember" 
                        id="remember" 
                        type="checkbox" 
                        class="form-checkbox h-4 w-4 text-teal-600 focus:ring-teal-400 rounded" 
                        name="remember">
                    <span class="ml-2 text-gray-700">Ghi nhớ đăng nhập</span>
                </label>
            </div>

            <x-primary-button wire:loading.attr="disabled" class="w-full justify-center py-3 font-semibold bg-teal-600 hover:bg-teal-700 focus:ring-teal-400 rounded-lg">
                <span wire:loading.remove>
                    Đăng nhập
                </span>

                <span wire:loading>
                    Đang đăng nhập...
                </span>
            </x-primary-button>
        </form>
    </div>
</div>
