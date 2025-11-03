<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitInvitation extends Model
{
    /** @use HasFactory<\Database\Factories\HabitInvitationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'habit_id',
        'inviter_id',
        'invitee_id',
        'status',
    ];
}
