<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use Livewire\Component;
use Livewire\WithPagination;

class ChallengeList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $challenges = Challenge::with('categories')
            ->where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(12);

        return view('livewire.challenges.challenge-list', [
            'challenges' => $challenges,
        ])->layout('layouts.app');
    }
}
