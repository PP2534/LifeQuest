<?php

namespace App\Livewire\Habits;

use App\Models\Habit;
use App\Models\HabitParticipant;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Habitcreate extends Component
{
    use WithFileUploads;

    public $title, $description, $image ,$type, $allow_request_join, $allow_member_invite, $start_date, $end_date;

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'type' => ['required', Rule::in(['personal', 'group'])],
            'allow_request_join'=> 'boolean',
            'allow_member_invite' => 'boolean',
            'start_date' =>'nullable|date',
            'end_date' =>'nullable|date|after_or_equal:start_date',
        ]);
        $path = $this->image
        ? $this->image->store('habits', 'public') // Lưu trong storage/app/public/habits
        : null;

        $habit = Habit::create([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $path,
            'type' =>$this->type,
            'allow_request_join' => (bool) $this->allow_request_join,
            'allow_member_invite' => (bool) $this->allow_member_invite,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'creator_id' => Auth::id() ?? 1, // tạm fix
        ]);
        // Thêm người tạo vào danh sách người tham gia
        HabitParticipant::create([
            'habit_id' => $habit->id,
            'user_id' => $habit->creator_id,
            'role' => 'creator',
            'status' => 'active',
        ]);
        session()->flash('success', 'Tạo thói quen thành công!');
        return redirect()->route('habits.index');
    }
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.habits.habitcreate');
    }
}
