<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="flex flex-col items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h1 class="text-3xl font-bold text-teal-600 mb-6 text-center">Đặt lại mật khẩu</h1>
        <form wire:submit="resetPassword" class="space-y-6">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email" class="mb-1 font-medium" />
                <x-text-input wire:model="email" id="email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400" type="email" name="email" required autofocus autocomplete="username" placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Mật khẩu mới" class="mb-1 font-medium" />
                <x-text-input wire:model="password" id="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" value="Xác nhận mật khẩu mới" class="mb-1 font-medium" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <x-primary-button class="w-full justify-center py-3 font-semibold bg-teal-600 hover:bg-teal-700 focus:ring-teal-400 rounded-lg">
                Đặt lại mật khẩu
            </x-primary-button>
        </form>
    </div>
</div>
