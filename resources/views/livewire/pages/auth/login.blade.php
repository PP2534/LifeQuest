<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();
        
        // Nếu user là admin, không cho đăng nhập ở user domain
        if(Auth::check() && Auth::user()->role === 'admin') {
            Auth::logout();
            $this->addError('form.email', 'Vui lòng đăng nhập tại trang quản trị.');
            return;
        }
        
        $this->redirectIntended(default: route('homepage', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h1 class="text-3xl font-bold text-teal-600 mb-6 text-center">Đăng nhập</h1>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email" class="mb-1 font-medium" />
                <x-text-input wire:model="form.email" id="email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400" type="email" name="email" required autofocus autocomplete="username" placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Mật khẩu" class="mb-1 font-medium" />
                <x-text-input wire:model="form.password" id="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember" class="inline-flex items-center text-sm">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="form-checkbox h-4 w-4 text-teal-600 focus:ring-teal-400 rounded" name="remember">
                    <span class="ml-2 text-gray-700">Ghi nhớ đăng nhập</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-teal-600 hover:text-teal-700 focus:outline-none focus:underline" href="{{ route('password.request') }}" wire:navigate>
                        Quên mật khẩu?
                    </a>
                @endif
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
        <p class="mt-6 text-center text-sm text-gray-600">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" wire:navigate class="text-teal-600 hover:text-teal-700 focus:outline-none focus:underline">Đăng ký ngay</a>
        </p>
    </div>
</div>
