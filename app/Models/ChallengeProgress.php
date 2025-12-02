<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeProgress extends Model
{
    /** @use HasFactory<\Database\Factories\ChallengeProgressFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'challenge_id',
        'challenge_participant_id',
        'user_id',
        'status',
        'proof_image',
        'date',
    ];
}
