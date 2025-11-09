<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class UserList extends Component
{
    public $users;
    public function mount(){
        $this->users = User::latest()->get();
    }
    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.users.user-list');
    }
}
