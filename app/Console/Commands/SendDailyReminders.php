<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\DailyReminderNotification;
use Carbon\Carbon;

class SendDailyReminders extends Command
{
    protected $signature = 'reminders:daily';
    protected $description = 'Gửi thông báo nhắc nhở thói quen & thử thách mỗi sáng';

    public function handle()
    {
        $today = Carbon::today();

        $users = User::with(['habits', 'challenges'])->get();

        foreach ($users as $user) {
            // Lọc ra những habit/challenge chưa hoàn thành hôm nay
            $missingHabits = $user->habits()
                ->whereDate('date', $today)
                ->where('status', '!=', 'done')
                ->pluck('name')
                ->toArray();

            $missingChallenges = $user->challenges()
                ->whereDate('date', $today)
                ->where('status', '!=', 'done')
                ->pluck('title')
                ->toArray();

            if ($missingHabits || $missingChallenges) {
                $user->notify(new DailyReminderNotification($missingHabits, $missingChallenges));
            }
        }

        $this->info('✅ Gửi nhắc nhở hằng ngày thành công.');
    }
}
