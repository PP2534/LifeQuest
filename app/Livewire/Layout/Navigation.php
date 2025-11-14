<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth;
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
        Auth::logout();
        $this->dispatch('refreshNavigation'); // update component
        return $this->redirect(route('homepage'), navigate: true);
    }

    public function render()
    {
        return view('livewire.layout.navigation');
    }
}
