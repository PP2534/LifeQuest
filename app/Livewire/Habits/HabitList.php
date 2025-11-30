<?php

namespace App\Livewire\Habits;

use Livewire\Component;
use App\Models\Habit;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class HabitList extends Component
{
    use WithPagination;

    public string $search = '';

    #[Layout('layouts.app')]
    public function render()
    {
        $userId = Auth::id();

        if ($userId) {
            // Lấy các thói quen mà người dùng là thành viên và có status là 'active'
            // Đồng thời, load thông tin participant của chính user đó để lấy streak
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
            })->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })->with([
                // Lấy thông tin participant của user hiện tại để hiển thị streak
                'participants' => fn($q) => $q->where('user_id', $userId),
                // Lấy thông tin lời mời của user hiện tại để hiển thị tag "Lời mời"
                'invitations' => fn($q) => $q->where('invitee_id', $userId)->where('status', 'pending'),
            ])->latest()->paginate(12);
        } else {
            // Nếu người dùng chưa đăng nhập, trả về một paginator rỗng
            $habits = Habit::where('id', -1)->paginate(12);
        }

        return view('livewire.habits.habit-list', ['habits' => $habits]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
