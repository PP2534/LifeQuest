<?php

namespace App\Livewire\Admin\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Navigation extends Component
{
    public $user;

    protected $listeners = ['refreshNavigation' => '$refresh'];

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return $this->redirect(route('admin.login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.layout.navigation');
    }
}
