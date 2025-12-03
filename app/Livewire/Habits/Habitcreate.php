<?php

namespace App\Livewire\Habits;

use App\Models\Activity;
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

    public string $title = '';
    public ?string $description = '';
    public $image = null;
    public string $type = 'personal';
    public bool $allow_request_join = false;
    public bool $allow_member_invite = false;
    public bool $need_proof = false;

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'type' => ['required', Rule::in(['personal', 'group'])],
            'allow_request_join'=> 'boolean',
            'allow_member_invite' => 'boolean',
            'need_proof' => 'boolean'
        ]);
        $path = $this->image
        ? $this->image->store('habits', 'public') // Lưu trong storage/app/public/habits
        : null;

        $habit = Habit::create([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $path,
            'type' => $this->type,
            // Nếu là 'personal', các giá trị này sẽ là false do đã khởi tạo.
            // Nếu là 'group', nó sẽ lấy giá trị từ checkbox (true/false).
            'allow_request_join' => (bool) $this->allow_request_join,
            'allow_member_invite' => (bool) $this->allow_member_invite,
            'need_proof' => (bool) $this->need_proof,
            'creator_id' => Auth::id() ?? 1, // tạm fix
        ]);
        // Thêm người tạo vào danh sách người tham gia
        HabitParticipant::create([
            'habit_id' => $habit->id,
            'user_id' => $habit->creator_id,
            'role' => 'creator',
            'status' => 'active',
        ]);

        Activity::create([
            'user_id' => $habit->creator_id,
            'type' => 'create_habit',
            'details' => (string) $habit->id,
        ]);

        $this->dispatch('activityAdded');
        session()->flash('success', 'Tạo thói quen thành công!');

        return $this->redirectRoute('habits.show', ['habit' => $habit->id], navigate: true);
    }
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.habits.habitcreate');
    }
}
