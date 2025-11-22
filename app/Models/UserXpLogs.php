<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserXpLogs extends Model
{
    /** @use HasFactory<\Database\Factories\UserXpLogsFactory> */
    use HasFactory;

    protected $table = "user_xp_logs";

    protected $fillable = [
        "user_id",
        "action",
        "xp"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
