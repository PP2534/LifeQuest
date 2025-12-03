<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0; // khởi tạo mặc định
    
    public function getListeners()
    {
        if (!Auth::check()) {
            return [];
        }

        $userId = Auth::id();
        return [
            "echo-private:App.Models.User.{$userId},.Illuminate\\Notifications\\Events\\DatabaseNotificationCreated" => 'refreshNotifications',
            'notificationAdded' => 'refreshNotifications',
            'challengeInvitationAdded' => 'refreshNotifications',
            'habitInvitationAdded' => 'refreshNotifications',
        ];
    }

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
            $this->dispatch('new-notification');
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
            $this->refreshNotifications();

            // Xử lý chuyển hướng dựa trên loại thông báo
            if (isset($notification->data['challenge_id']) && !isset($notification->data['comment_id'])) {
                // Chuyển hướng đến trang chi tiết thử thách
                return $this->redirect(route('challenges.show', $notification->data['challenge_id']), navigate: true);
            } elseif (isset($notification->data['challenge_id']) && isset($notification->data['comment_id'])) {
                // Chuyển hướng đến trang chi tiết thử thách và trỏ tới đúng bình luận
                $url = route('challenges.show', ['challenge' => $notification->data['challenge_id']]);
                return $this->redirect($url . '#comment-' . $notification->data['comment_id'], navigate: true);
            } elseif (isset($notification->data['challenge_invitation_id'])) {
                // Chuyển hướng đến trang chi tiết thử thách từ lời mời
                return $this->redirect(route('challenges.show', $notification->data['challenge_invitation_id']), navigate: true);
            } elseif (isset($notification->data['habit_invitation_id'])) {
                // Chuyển hướng đến trang chi tiết thói quen từ lời mời
                return $this->redirect(route('habits.show', $notification->data['habit_invitation_id']), navigate: true);
            } elseif (isset($notification->data['follower_id'])) {
                // Chuyển hướng đến trang cá nhân của người theo dõi
                $url = route('profile.show', ['id' => $notification->data['follower_id']]);
                return $this->redirect($url, navigate: true);
            }
        }
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead()
    {
        // Ensure a user is authenticated
        if (!Auth::check()) {
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->unreadNotifications->markAsRead();

        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
