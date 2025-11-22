<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ChallengeParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id', 'user_id', 'progress_percent', 'streak',
        'role', 'personal_start_date', 'personal_end_date', 'status',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function challenge(): BelongsTo { return $this->belongsTo(Challenge::class, 'challenge_id'); }
    
    // Quan hệ lấy lịch sử tiến độ
    public function progressLogs(): HasMany
    {
        return $this->hasMany(ChallengeProgress::class, 'challenge_participant_id');
    }

    /**
     * Hàm quan trọng: Tính toán lại Tiến độ và Chuỗi (Streak)
     */
    public function recalculateStats()
    {
        // 1. Tính % Tiến độ
        $totalDays = $this->challenge->duration_days;
        $doneCount = $this->progressLogs()->where('status', 'done')->count();
        
        $percent = ($totalDays > 0) ? min(100, round(($doneCount / $totalDays) * 100)) : 0;
        
        // 2. Tính Streak (Chuỗi liên tục)
        // Lấy tất cả ngày 'done', sắp xếp mới nhất trước
        $doneDates = $this->progressLogs()
            ->where('status', 'done')
            ->orderBy('date', 'desc')
            ->pluck('date') // Lấy cột date
            ->map(fn($date) => Carbon::parse($date)->startOfDay()); // Chuan hoa ve 00:00:00

        $streak = 0;
        $checkDate = Carbon::today()->startOfDay();

        // Nếu hôm nay chưa làm, bắt đầu kiểm tra từ hôm qua
        if ($doneDates->isEmpty() || $doneDates->first()->lt($checkDate)) {
             // Nếu muốn streak giữ nguyên dù hôm nay chưa làm, ta check từ hôm qua. 
             // Nhưng logic streak thường là phải liên tục. 
             // Tạm thời ta kiểm tra liên tục lùi dần.
             if ($doneDates->isNotEmpty() && $doneDates->first()->eq(Carbon::yesterday()->startOfDay())) {
                 $checkDate = Carbon::yesterday()->startOfDay();
             }
        }

        foreach ($doneDates as $date) {
            if ($date->eq($checkDate)) {
                $streak++;
                $checkDate->subDay(); // Lùi lại 1 ngày để kiểm tra tiếp
            } else {
                break; // Ngắt chuỗi
            }
        }

        // 3. Lưu vào Database
        $this->update([
            'progress_percent' => $percent,
            'streak' => $streak
        ]);
    }
}