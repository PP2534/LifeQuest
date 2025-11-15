<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    /** @use HasFactory<\Database\Factories\HabitFactory> */
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
        'type',
        'creator_id',
        'allow_request_join',
        'allow_member_invite',
        'need_proof',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'allow_request_join' => 'boolean',
        'allow_member_invite' => 'boolean',
        'need_proof' => 'boolean',
    ];
        /**
     * Get the participants for the habit.
     */
    public function participants()
    {
        return $this->hasMany(\App\Models\HabitParticipant::class);
    }

    /**
     * Get the invitations for the habit.
     */
    public function invitations()
    {
        return $this->hasMany(\App\Models\HabitInvitation::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (Habit $habit) {
            // Khi một thói quen bị xóa, cũng xóa tất cả những người tham gia nó.
            $habit->participants()->delete();
        });
    }

}
