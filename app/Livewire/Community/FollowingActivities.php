<?php

namespace App\Livewire\Community;

use App\Models\Activity;
use App\Models\Challenge;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowingActivities extends Component
{
    public $feed;
    public $challengeLookup;
    public $habitLookup;
    public $followedLookup;
    protected $listeners = ['activityAdded' => 'loadFeed'];

    public function mount()
    {
        $this->loadFeed();
    }

    public function loadFeed()
    {
        $followingIds = Auth::user()->followingsUsers->pluck('id');
        $this->feed = Activity::with('user')
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->take(20)
            ->get();

        $this->hydrateLookups();
    }

    protected function hydrateLookups(): void
    {
        $challengeIds = $this->feed
            ->where('type', 'create_challenge')
            ->pluck('details')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $habitIds = $this->feed
            ->where('type', 'create_habit')
            ->pluck('details')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $followedIds = $this->feed
            ->where('type', 'follow')
            ->pluck('details')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $this->challengeLookup = Challenge::whereIn('id', $challengeIds)->get()->keyBy('id');
        $this->habitLookup = Habit::whereIn('id', $habitIds)->get()->keyBy('id');
        $this->followedLookup = User::whereIn('id', $followedIds)->get()->keyBy('id');
    }

    public function render()
    {
        return view('livewire.community.following-activities', [
            'challengeLookup' => $this->challengeLookup ?? collect(),
            'habitLookup' => $this->habitLookup ?? collect(),
            'followedLookup' => $this->followedLookup ?? collect(),
        ]);
    }
}
