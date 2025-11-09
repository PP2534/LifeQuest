<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Get the habit that the invitation belongs to.
     */
    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

    /**
     * Get the user who invited.
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    /**
     * Get the user who was invited.
     */
    public function invitee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
