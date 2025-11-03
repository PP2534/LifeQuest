<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\HabitParticipantFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'habit_id',
        'user_id',
        'streak',
        'role',
        'status',
    ];
}
