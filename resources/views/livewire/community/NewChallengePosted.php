<?php

namespace App\Notifications;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChallengePosted extends Notification implements ShouldQueue
{
    use Queueable;

    public $challenge;
    public $poster;

    /**
     * Create a new notification instance.
     */
    public function __construct(Challenge $challenge, User $poster)
    {
        $this->challenge = $challenge;
        $this->poster = $poster;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'challenge_id' => $this->challenge->id,
            'challenge_title' => $this->challenge->title,
            'poster_id' => $this->poster->id,
            'poster_name' => $this->poster->name,
            'message' => "{$this->poster->name} vừa tạo một thử thách mới: {$this->challenge->title}",
        ];
    }
}