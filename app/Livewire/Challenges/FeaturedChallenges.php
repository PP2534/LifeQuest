<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class FeaturedChallenges extends Component
{
    public Collection $challenges;

    /**
     * Mount the component and fetch the featured challenges.
     */
    public function mount(): void
    {
        $this->challenges = Challenge::with('categories')
            ->withCount('participants')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->where('allow_request_join', true)
            ->orderByDesc('participants_count')
            ->take(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.challenge.featured-challenges');
    }
}