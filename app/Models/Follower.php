<?php

namespace App\Models;

use App\Notifications\NewFollowerNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class Follower extends Model
{
    /** @use HasFactory<\Database\Factories\FollowerFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'follower_id',
        'following_id',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($follower) {
            /** @var \App\Models\User $userBeingFollowed */
            $userBeingFollowed = $follower->following;
            /** @var \App\Models\User $followerUser */
            $followerUser = $follower->follower;

            Notification::send($userBeingFollowed, new NewFollowerNotification($followerUser));
        });
    }

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
