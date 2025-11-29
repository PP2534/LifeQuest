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

    #[Layout('layouts.app')]
    public function render()
    {
        $userId = Auth::id();
        $habits = Habit::query(); // Bắt đầu với một query builder trống

        if ($userId) {
            // Lấy các thói quen mà người dùng là thành viên và có status là 'active'
            // Đồng thời, load thông tin participant của chính user đó để lấy streak
            $habits = Habit::whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 'active');
            })->with(['participants' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->latest()->paginate(3);
        } else {
            // Nếu người dùng chưa đăng nhập, trả về một paginator rỗng
            $habits = Habit::where('id', -1)->paginate(3);
        }

        return view('livewire.habits.habit-list', ['habits' => $habits]);
    }
}
