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
            // Lấy các thói quen mà người dùng là thành viên và có status là 'active'
            // Đồng thời, load thông tin participant của chính user đó để lấy streak
            $this->habits = Habit::whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 'active');
            })->with(['participants' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->latest()->get();
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
