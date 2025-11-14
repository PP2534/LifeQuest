<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Get the progress records for the challenge participant.
     */
    public function progress()
    {
        return $this->hasMany(ChallengeProgress::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
