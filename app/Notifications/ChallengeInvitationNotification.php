<?php

namespace App\Notifications;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ChallengeInvitationNotification extends Notification
{
    use Queueable;

    public User $inviter;
    public Challenge $challenge;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $inviter, Challenge $challenge)
    {
        $this->inviter = $inviter;
        $this->challenge = $challenge;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Lời mời tham gia thử thách mới!')
                    ->greeting('Xin chào ' . $notifiable->name . ',')
                    ->line($this->inviter->name . ' đã mời bạn tham gia thử thách: "' . $this->challenge->title . '".')
                    ->action('Xem thử thách', url('/challenges/' . $this->challenge->id))
                    ->line('Hãy tham gia ngay để không bỏ lỡ nhé!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'inviter_id' => $this->inviter->id,
            'inviter_name' => $this->inviter->name,
            'challenge_id' => $this->challenge->id,
            'challenge_title' => $this->challenge->title,
            'message' => $this->inviter->name . ' đã mời bạn tham gia thử thách: ' . $this->challenge->title,
        ];
    }
}
