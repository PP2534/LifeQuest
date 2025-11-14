<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DailyReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $missingHabits;
    public $missingChallenges;

    public function __construct($missingHabits = [], $missingChallenges = [])
    {
        $this->missingHabits = $missingHabits;
        $this->missingChallenges = $missingChallenges;
    }

    public function via($notifiable)
    {
        // gửi qua database (bảng notifications)
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nhắc nhở ngày mới 🌞',
            'message' => $this->buildMessage(),
            'missing_habits' => $this->missingHabits,
            'missing_challenges' => $this->missingChallenges,
        ];
    }

    protected function buildMessage()
    {
        $parts = [];
        if ($this->missingHabits) {
            $parts[] = 'Bạn còn ' . count($this->missingHabits) . ' thói quen chưa hoàn thành.';
        }
        if ($this->missingChallenges) {
            $parts[] = 'Bạn còn ' . count($this->missingChallenges) . ' thử thách chưa thực hiện.';
        }

        return implode(' ', $parts) ?: 'Chúc bạn một ngày mới tràn đầy năng lượng!';
    }
}
