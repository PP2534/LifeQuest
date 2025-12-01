<?php

namespace App\Notifications;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HabitInvitationNotification extends Notification
{
    use Queueable;

    public User $inviter;
    public Habit $habit;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $inviter, Habit $habit)
    {
        $this->inviter = $inviter;
        $this->habit = $habit;
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
                    ->subject('Lời mời tham gia thói quen mới!')
                    ->greeting('Xin chào ' . $notifiable->name . ',')
                    ->line($this->inviter->name . ' đã mời bạn tham gia thói quen: "' . $this->habit->name . '".')
                    ->action('Xem thói quen', url('/habits/' . $this->habit->id))
                    ->line('Hãy tham gia ngay để cùng nhau phát triển nhé!');
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
            'habit_id' => $this->habit->id,
            'habit_name' => $this->habit->name,
            'message' => $this->inviter->name . ' đã mời bạn tham gia thói quen: ' . $this->habit->name,
        ];
    }
}
