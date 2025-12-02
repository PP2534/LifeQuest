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
    
    public function progressLogs(): HasMany
    {
        return $this->hasMany(ChallengeProgress::class, 'challenge_participant_id');
    }

    /**
     * Tính toán Tiến độ và Chuỗi (Streak) dựa trên streak_mode
     */
    public function recalculateStats()
    {
        // 1. Tính % Tiến độ (Giống nhau cho cả 2 loại)
        // Đếm tổng số ngày đã 'done'
        $doneCount = $this->progressLogs()->where('status', 'done')->count();
        $totalDays = $this->challenge->duration_days;
        
        // Tính phần trăm
        $percent = ($totalDays > 0) ? min(100, round(($doneCount / $totalDays) * 100)) : 0;
        
        // 2. Tính Streak dựa trên chế độ (Mode)
        $streak = 0;
        $mode = $this->challenge->streak_mode; // 'continuous' hoặc 'cumulative'

        if ($mode === 'cumulative') {
            // === CHẾ ĐỘ TÍCH LŨY ===
            // Streak đơn giản là tổng số ngày đã hoàn thành
            $streak = $doneCount;
        } else {
            // === CHẾ ĐỘ LIÊN TỤC (Mặc định) ===
            // Lấy danh sách các ngày đã làm (sắp xếp mới nhất trước)
            $doneDates = $this->progressLogs()
                ->where('status', 'done')
                ->orderBy('date', 'desc')
                ->pluck('date')
                ->map(fn($date) => Carbon::parse($date)->startOfDay()); // Chuẩn hóa giờ về 00:00

            if ($doneDates->isNotEmpty()) {
                // Xác định ngày bắt đầu kiểm tra (Mốc)
                $today = Carbon::today()->startOfDay();
                $yesterday = Carbon::yesterday()->startOfDay();
                $latestDone = $doneDates->first();

                // Nếu ngày làm gần nhất không phải là Hôm nay hoặc Hôm qua
                // => Chuỗi đã bị đứt.
                if ($latestDone->lt($yesterday)) {
                    $streak = 0;
                } else {
                    // Bắt đầu đếm ngược từ ngày làm gần nhất
                    $streak = 0;
                    $checkDate = $latestDone->copy();

                    foreach ($doneDates as $date) {
                        if ($date->eq($checkDate)) {
                            $streak++;
                            // Lùi lại 1 ngày để kiểm tra ngày tiếp theo trong quá khứ
                            $checkDate->subDay();
                        } else {
                            // Nếu ngày trong list không khớp với ngày liền kề -> đứt chuỗi
                            break;
                        }
                    }
                }
            }
        }

        // 3. Lưu vào Database
        $this->update([
            'progress_percent' => $percent,
            'streak' => $streak
        ]);
    }
}