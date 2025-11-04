<?php

namespace App\Livewire\Habits;

use App\Models\Habit;
use App\Models\HabitParticipant;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class HabitShow extends Component
{
    public Habit $habit;
    public bool $isParticipant = false;
    public bool $isCreator = false;

    public function mount(Habit $habit)
    {
        // Nạp trước toàn bộ quan hệ cần thiết ngay khi mount
        $this->habit = $habit->load(['participants.user']);

        $this->loadParticipationData();
    }

    public function joinHabit()
    {
        if ($this->habit->type !== 'group' || $this->isParticipant) {
            return;
        }

        HabitParticipant::create([
            'habit_id' => $this->habit->id,
            'user_id' => Auth::id(),
            'role' => 'member',
            'status' => 'active',
        ]);

        //  Nạp lại toàn bộ dữ liệu sau khi thay đổi
        $this->habit->refresh()->load(['participants.user']);
        $this->loadParticipationData();

        session()->flash('status', 'Bạn đã tham gia thói quen thành công!');
    }

    public function leaveHabit()
    {
        if (!$this->isParticipant || $this->isCreator) {
            return;
        }

        HabitParticipant::where('habit_id', $this->habit->id)
            ->where('user_id', Auth::id())
            ->delete();

        // Làm mới dữ liệu sau khi rời nhóm
        $this->habit->refresh()->load(['participants.user']);
        $this->loadParticipationData();

        session()->flash('status', 'Bạn đã rời khỏi thói quen.');
    }

    public function deleteHabit()
    {
        // Chỉ người tạo mới có quyền xóa
        if (!$this->isCreator) {
            session()->flash('error', 'Bạn không có quyền xóa thói quen này.');
            return;
        }

        $this->habit->delete();

        // Chuyển hướng về trang danh sách với thông báo
        session()->flash('status', 'Thói quen đã được xóa thành công.');
        return redirect()->route('habits.index')->with('success', 'Habit deleted successfully.');
    }

    protected function loadParticipationData(): void
    {
        //  Nếu habit chưa có participants, tránh lỗi null
        $participants = $this->habit->participants ?? collect();

        $currentUserParticipant = $participants->firstWhere('user_id', Auth::id());

        $this->isParticipant = (bool) $currentUserParticipant;
        $this->isCreator = $currentUserParticipant && $currentUserParticipant->role === 'creator';
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.habits.habit-show');
    }
}
