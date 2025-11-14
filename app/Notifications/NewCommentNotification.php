<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public Comment $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        $challenge = $this->comment->challenge;
        $commenter = $this->comment->user;

        return [
            'title' => 'Bình luận mới 💬',
            'message' => "{$commenter->name} đã bình luận về thử thách '{$challenge->title}'.",
            'challenge_id' => $challenge->id,
            'comment_id' => $this->comment->id,
            'commenter_name' => $commenter->name,
        ];
    }
}
