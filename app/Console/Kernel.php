<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Đăng ký các command Artisan của ứng dụng.
     */
    protected function commands()
    {
        // Load các command trong thư mục app/Console/Commands
        $this->load(__DIR__.'/Commands');

        // Nếu bạn có file routes/console.php thì include nó (tuỳ chọn)
        require base_path('routes/console.php');
    }

    /**
     * Định nghĩa lịch chạy (scheduler) cho các command.
     */
    protected function schedule(Schedule $schedule)
    {
        // Ví dụ: gửi thông báo tự động lúc 7h sáng mỗi ngày
        $schedule->command('reminders:daily')->dailyAt('07:00');

        // Bạn có thể thêm nhiều schedule khác ở đây
        // $schedule->command('emails:send')->mondays()->at('08:00');
    }
}
