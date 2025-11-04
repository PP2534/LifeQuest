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
        $this->habits = Habit::with(['participants' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->latest()->get();
    }
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.habits.habit-list');
    }
}
