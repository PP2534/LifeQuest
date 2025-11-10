<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\ChallengeParticipantFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'challenge_id',
        'user_id',
        'status',
        'role',
        'personal_start_date',
        'personal_end_date',
        'progress_percent',
        'streak',
    ];
    /**
     * Lấy người dùng tham gia thử thách.
     */
    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lấy thử thách.
     */
    public function challenge(): BelongsTo 
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }
}
