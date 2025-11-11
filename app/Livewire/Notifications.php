<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public $notifications; //ds thong bao
    public $unreadCount; // đêm số lượng thông báo chưa đọc

    protected $listeners = ['notificationAdded' => 'mount'];

    
    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->notifications = $user->notifications;
        $this->unreadCount = $user->unreadNotifications->count();
    }

    public function markAsRead($notificationId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
        }

        $this->mount(); // cập nhật lại danh sách
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
