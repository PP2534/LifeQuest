<?php

namespace App\Livewire\Challenges;

use Livewire\Component;
use App\Models\Challenge;

class ChallengeList extends Component
{
    public $challenges;
    public function mount(){
        $this->challenges = Challenge::with('categories')->latest()->get();
    }
    public function render()
    {
        return view('livewire.challenges.challenge-list')
            ->layout('layouts.app');
    }
}
