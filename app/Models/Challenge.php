<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
        'need_proof',
        'status',
        'creator_id',
        'ward_id',
    ];

    /**
     * Lấy người tạo ra thử thách.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Lấy tất cả người tham gia thử thách.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(ChallengeParticipant::class);
    }

    /**
     * The categories that belong to the challenge.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'challenge_categories');
    }
    /**
     * Lấy tất cả bình luận của thử thách.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    /** 
    * Tự động tính toán trạng thái dựa trên ngày kết thúc.
     */
    public function getStatusAttribute(): string
    {
        if ($this->end_date && Carbon::parse($this->end_date)->isPast()) {
            return 'Hoàn thành';
        }
        
        // (có thể thêm logic cho 'Sắp diễn ra' nếu $this->start_date > now())

        return 'Đang diễn ra';
    }

   public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
}
