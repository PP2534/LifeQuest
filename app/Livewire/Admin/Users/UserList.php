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
        ->latest()
        ->paginate(3);
        return view('livewire.admin.users.user-list', compact('users'));
    }
}
