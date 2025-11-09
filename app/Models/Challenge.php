<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * The categories that belong to the challenge.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'challenge_categories');
    }
}
