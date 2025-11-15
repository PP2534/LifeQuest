<?php

namespace App\Livewire\Community;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowingActivities extends Component
{
    public $feed;
    protected $listeners = ['activityAdded' => 'loadFeed'];

    public function mount()
    {
        $this->loadFeed();
    }

    public function loadFeed()
    {
        $followingIds = Auth::user()->followingsUsers->pluck('id');
        $this->feed = Activity::whereIn('user_id', $followingIds)
            ->latest()
            ->take(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.community.following-activities');
    }
}
