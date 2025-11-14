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

    // Gán layout admin
    #[Layout('layouts.admin')]
    public function render()
    {
        // Lấy tất cả user trừ user hiện tại, phân trang 5 bản ghi
        $users = User::where('id', '!=', Auth::id())
                     ->latest()
                     ->paginate(3);

        return view('livewire.admin.users.user-list', compact('users'));
    }
}
