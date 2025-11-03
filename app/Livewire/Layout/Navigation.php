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
        $this->emit('refreshNavigation'); // update component
        return redirect()->route('homepage');
    }

    public function render()
    {
        return view('livewire.layout.navigation');
    }
}
