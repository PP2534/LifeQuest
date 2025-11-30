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
            $habits = Habit::whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('status', 'active');
            })->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })->with(['participants' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->latest()->paginate(12);
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
