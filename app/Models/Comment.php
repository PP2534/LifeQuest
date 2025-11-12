<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'challenge_id',
        'content',
    ];
    /**
     * Lấy người dùng đã viết bình luận.
     */
    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lấy thử thách mà bình luận này thuộc về.
     */
    public function challenge(): BelongsTo 
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }
}
