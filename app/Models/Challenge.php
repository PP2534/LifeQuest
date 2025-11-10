<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    /** @use HasFactory<\Database\Factories\ChallengeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'time_mode',
        'streak_mode',
        'duration_days',
        'start_date',
        'end_date',
        'type',
        'allow_request_join',
        'allow_member_invite',
        'creator_id',
    ];

    /**
     * Lấy người tạo ra thử thách.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Lấy các danh mục của thử thách.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'challenge_categories');
    }

    /**
     * Lấy tất cả người tham gia thử thách.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(ChallengeParticipant::class);
    }

    /**
     * Lấy tất cả bình luận của thử thách.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}