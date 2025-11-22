<?php

namespace App\Livewire\Challenges;

use Livewire\Component;
use App\Models\Challenge;
use Livewire\WithPagination;


class ChallengeList extends Component
{
    use WithPagination;
    public function render()
    {
        $challenges = Challenge::with('categories')
            ->where('status', 'active') 
            ->latest()
            ->paginate(3);

        return view('livewire.challenges.challenge-list', [
            'challenges' => $challenges,
        ])->layout('layouts.app');
    }
}
