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

    #[Layout('layouts.admin')]
    public function render()
    {
        $users = User::where('id', '!=', Auth::id())
            ->with('ward.province')
            ->latest()
            ->paginate(10);
        return view('livewire.admin.users.user-list', compact('users'));
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
}
