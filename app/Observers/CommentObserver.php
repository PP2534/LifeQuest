<?php

namespace App\Observers;

use App\Notifications\NewCommentNotification;
use App\Models\Comment;
use App\Services\XpService;

class CommentObserver
{
    public function __construct(private readonly XpService $xpService)
    {
    }

    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        // Tải các mối quan hệ cần thiết
        $comment->load('challenge.creator', 'user');

        $challenge = $comment->challenge;
        $challengeCreator = $challenge->creator;
        $commenter = $comment->user;

        // Kiểm tra xem người tạo thử thách có tồn tại và không phải là người vừa bình luận
        if ($challengeCreator && $commenter && $challengeCreator->id !== $commenter->id) {
            // Gửi thông báo cho người tạo thử thách
            $challengeCreator->notify(new NewCommentNotification($comment));
        }

        if ($commenter) {
            $this->xpService->awardDailyCommentXp($commenter);
        }
    }
}