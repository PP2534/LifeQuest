<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeInvitation extends Model
{
    /** @use HasFactory<\Database\Factories\ChallengeInvitationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'challenge_id',
        'inviter_id',
        'invitee_id',
        'status',
    ];
}
