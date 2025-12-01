<?php

namespace App\Notifications;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChallengePosted extends Notification
{
    use Queueable;

    public $challenge;

    /**
     * Create a new notification instance.
     */
    public function __construct(Challenge $challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $creatorName = $this->challenge->creator ? $this->challenge->creator->name : 'Người dùng ẩn danh';

        return [
            'user_id' => $this->challenge->creator ? $this->challenge->creator->id : null,
            'user_name' => $creatorName,
            'challenge_id' => $this->challenge->id,
            'challenge_title' => $this->challenge->title,
            'message' => $creatorName . ' vừa tạo một thử thách mới: ' . $this->challenge->title,
        ];
    }
}
