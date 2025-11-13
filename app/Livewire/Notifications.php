<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0; // khởi tạo mặc định

    protected $listeners = ['notificationAdded' => 'refreshNotifications'];

    public function refreshNotifications()
    {
        // Ensure a user is authenticated before attempting to fetch notifications.
        if (!Auth::check()) {
            $this->notifications = [];
            $this->unreadCount = 0;
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $oldCount = $this->unreadCount;

        $this->notifications = $user->notifications;
        $this->unreadCount = $user->unreadNotifications->count();

        if ($this->unreadCount > $oldCount) {
            $this->dispatchBrowserEvent('new-notification');
        }
    }

    public function mount()
    {
        $this->refreshNotifications();
    }

    /**
     * Mark a notification as read.
     *
     * @param string $notificationId
     */
    public function markAsRead(string $notificationId)
    {
        // Ensure a user is authenticated before attempting to mark notifications as read.
        if (!Auth::check()) {
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();


        $notification = $user->notifications()->find($notificationId);


        if ($notification) {
            $notification->markAsRead();
        }

        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
