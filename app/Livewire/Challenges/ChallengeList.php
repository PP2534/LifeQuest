<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use Livewire\Component;
use Livewire\WithPagination;

class ChallengeList extends Component
{
    use WithPagination;

    public function render()
    {
        $challenges = Challenge::with('categories')->latest()->paginate(3);
        return view('livewire.challenges.challenge-list', [
            'challenges' => $challenges,
        ])->layout('layouts.app');
    }
}
