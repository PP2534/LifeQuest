<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class CustomResetPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Khôi phục mật khẩu LifeQuest')
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.')
            ->action('Đặt lại mật khẩu', $url)
            ->line('Nếu bạn không yêu cầu hành động này, hãy bỏ qua email này.')
            ->salutation('Cảm ơn bạn đã sử dụng LifeQuest');
    }
}
