<?php

namespace App\Models;

use App\Services\XpService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    /**
     * Get the logs for the habit participant.
     */
    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }

    /**
     * Calculates the current streak based on logs and updates the model.
     */
    public function calculateAndUpdateStreak(XpService $xpService): void
    {
        // Lấy tất cả các bản ghi log, sắp xếp giảm dần, để đảm bảo cast 'date' hoạt động.
        $logModels = $this->logs()->orderBy('date', 'desc')->get();
        $oldStreak = $this->streak;

        if ($logModels->isEmpty()) {
            $this->streak = 0;
            $this->save();
            return;
        }

        // Lấy ngày của bản ghi log đầu tiên và đảm bảo nó là đối tượng Carbon.
        $latestLogDate = $logModels->first()->date;
        if (!$latestLogDate instanceof Carbon) {
            $latestLogDate = Carbon::parse($latestLogDate);
        }

        $streak = 0;
        $today = Carbon::today();

        // Nếu lần log cuối cùng không phải hôm nay hoặc hôm qua, chuỗi đã bị ngắt.
        if (!$latestLogDate->isToday() && !$latestLogDate->isYesterday()) {
            $this->streak = 0;
            $this->save();
            return;
        }

        // Chuyển sang mảng để tra cứu nhanh hơn
        $logDates = $logModels->mapWithKeys(function ($log) {
            $date = $log->date instanceof Carbon ? $log->date : Carbon::parse($log->date);
            return [$date->format('Y-m-d') => true];
        });

        // Bắt đầu đếm từ hôm nay. Nếu hôm nay chưa log, bắt đầu từ hôm qua.
        $currentDate = $today->copy();
        if (!$logDates->has($currentDate->format('Y-m-d'))) {
            $currentDate->subDay();
        }

        // Đếm ngược các ngày liên tiếp
        while ($logDates->has($currentDate->format('Y-m-d'))) {
            $streak++;
            $currentDate->subDay();
        }

        $this->streak = $streak;
        $this->save();

        // Cộng điểm XP nếu đạt mốc streak mới
        if ($this->streak > $oldStreak) {
            $user = $this->user;
            $xpService->awardHabitStreakXp($user, $this->streak);
        }
    }
}