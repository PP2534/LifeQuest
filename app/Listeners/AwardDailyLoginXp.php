<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\XpService;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardDailyLoginXp
{
    protected $xpService;

    /**
     * Create the event listener.
     */
    public function __construct(XpService $xpService)
    {
        $this->xpService = $xpService;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Đảm bảo event được kích hoạt cho model User của bạn
        // và người dùng không phải là admin
        if ($event->user instanceof User && $event->user->role !== 'admin') {
            $this->xpService->awardDailyLoginXp($event->user);
        }
    }
}