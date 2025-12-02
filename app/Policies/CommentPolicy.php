<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Cho phép admin xóa mọi thứ.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Xác định xem người dùng có thể xóa bình luận hay không.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Người dùng có thể xóa bình luận của chính họ.
        return $user->id === $comment->user_id;
    }
}