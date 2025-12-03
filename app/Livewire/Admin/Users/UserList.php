<?php

namespace App\Livewire\Admin\Users;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserList extends Component
{
    use WithPagination;

    public array $availableRoles = ['user', 'admin'];

    #[Layout('layouts.admin')]
    public function render()
    {
        $users = User::with('ward.province')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.users.user-list', [
            'users' => $users,
        ]);
    }

    public function updateUserStatus($userId, $status)
    {
        $user = User::findOrFail($userId);
        if (in_array($status, ['active', 'banned'])) {
            $user->status = $status;
            $user->save();
            session()->flash('message', 'Cập nhật trạng thái người dùng thành công.');
        }
    }

    public function updateUserRole($userId, $role)
    {
        if (!in_array($role, $this->availableRoles, true)) {
            return;
        }

        if ((int) $userId === (int) Auth::id()) {
            session()->flash('message', 'Không thể thay đổi quyền của chính bạn từ trang này.');
            return;
        }

        $user = User::findOrFail($userId);
        $user->role = $role;
        $user->save();

        session()->flash('message', 'Đã cập nhật quyền người dùng.');
    }
}
