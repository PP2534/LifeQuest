<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use App\Notifications\CustomResetPassword;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;


/** @mixin \Illuminate\Notifications\HasDatabaseNotifications */
/** @mixin \Illuminate\Database\Eloquent\Builder */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'interests',
        'role',
        'status',
        'provider',
        'provider_id',
        'email_verified_at',
        'ward_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
    /**
     * Các thử thách do người dùng này tạo.
     */
    public function challenges(): HasMany // 
    {
        return $this->hasMany(Challenge::class, 'creator_id');
    }

    /**
     * Các thử thách mà người dùng này tham gia.
     */
    public function participations(): HasMany 
    {
        return $this->hasMany(ChallengeParticipant::class, 'user_id');
    }

    /**
     * Các bình luận của người dùng này.
     */
    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class, 'user_id');
    }
    public function habits()
    {
        return $this->hasMany(\App\Models\Habit::class);
    }
}
