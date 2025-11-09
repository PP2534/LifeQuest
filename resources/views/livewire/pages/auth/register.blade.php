<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h1 class="text-3xl font-bold text-teal-600 mb-6 text-center">Đăng ký</h1>
        <form wire:submit="register" class="space-y-6">
            <!-- Name -->
            <div>
                <x-input-label for="name" value="Họ tên" class="mb-1 font-medium" />
                <x-text-input wire:model="name" id="name" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400" type="text" name="name" required autofocus autocomplete="name" placeholder="Nguyễn Văn A" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email" class="mb-1 font-medium" />
                <x-text-input wire:model="email" id="email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400" type="email" name="email" required autocomplete="username" placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Mật khẩu" class="mb-1 font-medium" />
                <x-text-input wire:model="password" id="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" value="Xác nhận mật khẩu" class="mb-1 font-medium" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <x-primary-button class="w-full justify-center py-3 font-semibold bg-teal-600 hover:bg-teal-700 focus:ring-teal-400 rounded-lg">
                Đăng ký
            </x-primary-button>
        </form>
        <p class="mt-6 text-center text-sm text-gray-600">
            Đã có tài khoản?
            <a href="{{ route('login') }}" wire:navigate class="text-teal-600 hover:text-teal-700 focus:outline-none focus:underline">Đăng nhập ngay</a>
        </p>
    </div>
</div>
