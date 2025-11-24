<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="flex flex-col items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h1 class="text-3xl font-bold text-teal-600 mb-4 text-center">Quên mật khẩu</h1>
        <div class="mb-4 text-sm text-gray-600">
            Bạn quên mật khẩu? Không vấn đề gì. Chỉ cần cho chúng tôi biết địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn một liên kết đặt lại mật khẩu để bạn có thể chọn một mật khẩu mới.
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-6 mt-6">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email" class="mb-1 font-medium" />
                <x-text-input wire:model="email" id="email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400" type="email" name="email" required autofocus placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <x-primary-button class="w-full justify-center py-3 font-semibold bg-teal-600 hover:bg-teal-700 focus:ring-teal-400 rounded-lg">
                <span wire:loading.remove>
                    Gửi liên kết đặt lại mật khẩu
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
