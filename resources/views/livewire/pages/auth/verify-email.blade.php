<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('homepage', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex flex-col items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h1 class="text-3xl font-bold text-teal-600 mb-4 text-center">Xác thực địa chỉ Email</h1>
        <div class="mb-4 text-sm text-gray-600">
            Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, bạn có thể xác minh địa chỉ email của mình bằng cách nhấp vào liên kết chúng tôi vừa gửi cho bạn không? Nếu bạn không nhận được email, chúng tôi sẽ sẵn lòng gửi cho bạn một email khác.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp trong quá trình đăng ký.
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between">
            <x-primary-button wire:loading.attr="disabled" wire:click="sendVerification" class="py-3 font-semibold bg-teal-600 hover:bg-teal-700 focus:ring-teal-400 rounded-lg">
                <span wire:loading.remove>
                    Gửi lại email xác thực
                </span>

                <span wire:loading>
                    Đang gửi email xác thực...
                </span>
            </x-primary-button>

            <button wire:click="logout" wire:loading.attr="disabled" type="submit" class="text-sm text-teal-600 hover:text-teal-700 focus:outline-none focus:underline">
                <span wire:loading.remove>
                    Đăng xuất
                </span>

                <span wire:loading>
                    Đang đăng xuất...
                </span>
            </button>
        </div>
    </div>
</div>
