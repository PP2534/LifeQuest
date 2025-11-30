<?php

namespace App\Livewire\Habits;

use Livewire\Component;
use App\Models\Habit;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class HabitList extends Component
{
    public $habits;

    public function mount()
    {
        $userId = Auth::id();

        if ($userId) {
            // Lấy các thói quen mà người dùng là thành viên HOẶC được mời
            $this->habits = Habit::where(function ($query) use ($userId) {
                // 1. Là thành viên đang hoạt động
                $query->whereHas('participants', function ($subQuery) use ($userId) {
                    $subQuery->where('user_id', $userId)
                             ->where('status', 'active');
                })
                // 2. HOẶC có lời mời đang chờ
                ->orWhereHas('invitations', function ($subQuery) use ($userId) {
                    $subQuery->where('invitee_id', $userId)
                             ->where('status', 'pending');
                });
            })
            // Eager load thông tin cần thiết để hiển thị
            ->with([
                // Lấy thông tin participant của user hiện tại để hiển thị streak
                'participants' => fn($q) => $q->where('user_id', $userId),
                // Lấy thông tin lời mời của user hiện tại để hiển thị tag "Lời mời"
                'invitations' => fn($q) => $q->where('invitee_id', $userId)->where('status', 'pending'),
            ])->latest()->get();
        } else {
            // Nếu người dùng chưa đăng nhập, không hiển thị thói quen nào
            $this->habits = collect();
        }
    }
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.habits.habit-list');
    }
}
