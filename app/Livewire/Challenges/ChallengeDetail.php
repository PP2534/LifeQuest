<?php

namespace App\Livewire\Challenges;

use Livewire\Component;
use App\Models\Challenge;

class ChallengeDetail extends Component
{
    public $challenge;

    public function mount($id)
    {
        $this->challenge = Challenge::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.challenges.challenge-detail')->layout('layouts.app');
    }
}
