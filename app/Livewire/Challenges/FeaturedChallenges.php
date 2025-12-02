<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;


class FeaturedChallenges extends Component
{
    public Collection $challenges;

    /**
     * Mount the component and fetch the featured challenges.
     */
    public function mount(): void
    {
        $this->challenges = Cache::remember('users', 600,fn()=> Challenge::with('categories')
            ->withCount('participants')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->where('type', 'public')
            ->orderByDesc('participants_count')
            ->take(6)
            ->get());
    }

    public function render()
    {
        return view('livewire.challenges.featured-challenges');
    }
}