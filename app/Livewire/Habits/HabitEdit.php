<?php

namespace App\Livewire\Habits;

use App\Models\Habit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class HabitEdit extends Component
{
    use WithFileUploads;

    public Habit $habit;

    public $title, $description, $type, $allow_request_join, $allow_member_invite, $start_date, $end_date;
    public $image; // Cho ảnh mới tải lên
    public $existingImage; // Để hiển thị ảnh hiện tại

    public function mount(Habit $habit)
    {
        // Đảm bảo người dùng là người tạo
        if ($habit->creator_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa thói quen này.');
        }

        $this->habit = $habit;
        $this->title = $habit->title;
        $this->description = $habit->description;
        $this->type = $habit->type;
        $this->allow_request_join = (bool) $habit->allow_request_join;
        $this->allow_member_invite = (bool) $habit->allow_member_invite;
        $this->start_date = $habit->start_date;
        $this->end_date = $habit->end_date;
        $this->existingImage = $habit->image;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Ảnh mới là tùy chọn
            'type' => ['required', Rule::in(['personal', 'group'])],
            'allow_request_join'=> 'boolean',
            'allow_member_invite' => 'boolean',
            'start_date' =>'nullable|date',
            'end_date' =>'nullable|date|after_or_equal:start_date',
        ]);

        $path = $this->existingImage;
        if ($this->image) {
            // Nếu có ảnh mới, lưu nó
            $path = $this->image->store('habits', 'public');
            // Xóa ảnh cũ nếu có
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
        }

        $this->habit->update([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $path,
            'type' =>$this->type,
            'allow_request_join' => (bool) $this->allow_request_join,
            'allow_member_invite' => (bool) $this->allow_member_invite,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        session()->flash('status', 'Cập nhật thói quen thành công!');
        return redirect()->route('habits.show', $this->habit);
    }

    public function render()
    {
        return view('livewire.habits.habit-edit');
    }
}