<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h1 class="text-3xl font-bold text-teal-600 mb-4 text-center">Xác nhận mật khẩu</h1>
        <div class="mb-4 text-sm text-gray-600">
            Đây là một khu vực an toàn của ứng dụng. Vui lòng xác nhận mật khẩu của bạn trước khi tiếp tục.
        </div>

        <form wire:submit="confirmPassword" class="space-y-6 mt-6">
            <!-- Password -->
            <div>
                <x-input-label for="password" value="Mật khẩu" class="mb-1 font-medium" />
                <x-text-input wire:model="password"
                              id="password"
                              class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400"
                              type="password"
                              name="password"
                              required autocomplete="current-password"
                              placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <x-primary-button class="w-full justify-center py-3 font-semibold bg-teal-600 hover:bg-teal-700 focus:ring-teal-400 rounded-lg">
                <span wire:loading.remove>
                    Xác nhận
                </span>

                <span wire:loading.flex class="items-center justify-center">
                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                        <path class="opacity-75" d="M4 12a8 8 0 018-8"></path>
                    </svg>
                </span>
            </x-primary-button>
        </form>
    </div>
</div>
