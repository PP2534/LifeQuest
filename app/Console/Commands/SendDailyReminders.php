<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Habit;
use App\Models\Challenge;
use App\Notifications\DailyReminderNotification;
use Carbon\Carbon;

class SendDailyReminders extends Command
{
    protected $signature = 'reminders:daily';
    protected $description = 'Gửi thông báo nhắc nhở thói quen & thử thách mỗi sáng';

    public function handle()
    {
        $today = Carbon::today();

        // Lấy tất cả người dùng đang hoạt động
        $users = User::where('status', 'active')->get();

        foreach ($users as $user) {
            // Lấy các thói quen người dùng đang tham gia nhưng chưa hoàn thành hôm nay
            $missingHabits = Habit::whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', 'active');
            })
            ->whereDoesntHave('participants.logs', function ($query) use ($user, $today) {
                $query->where('habit_participants.user_id', $user->id)
                      ->where('status', 'done')
                      ->whereDate('date', $today);
            })
            ->pluck('title')
            ->toArray();

            // Lấy các thử thách người dùng đang tham gia nhưng chưa hoàn thành hôm nay
            $missingChallenges = Challenge::whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', 'active');
            })
            ->whereDoesntHave('participants.progress', function ($query) use ($user, $today) {
                $query->where('challenge_participants.user_id', $user->id)
                      ->where('status', 'done')
                      ->whereDate('date', $today);
            })
            ->pluck('title')
                ->toArray();

            // Chỉ gửi thông báo nếu có ít nhất một mục chưa hoàn thành
            if (!empty($missingHabits) || !empty($missingChallenges)) {
                $user->notify(new DailyReminderNotification($missingHabits, $missingChallenges));
            }
        }

        $this->info('✅ Gửi nhắc nhở hằng ngày thành công.');
    }
}
