<?php

namespace App\Livewire\Community;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowingFollowersList extends Component
{
    public $followers = [];
    public $followings = [];
    public $activeTab = 'following'; 
    protected $listeners = ['refreshFollowList' => '$refresh'];

    public function mount()
    {
        $this->loadFollowings();
        $this->loadFollowers();
    }

    public function loadFollowings()
    {
        $this->followings = Auth::user()->followingsUsers->where('role', '!=', 'admin');
    }

    public function loadFollowers()
    {
        $this->followers = Auth::user()->followersUsers->where('role', '!=', 'admin');
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function follow($userId)
    {
        Auth::user()->followingsUsers()->attach($userId);
        $this->loadFollowings();
        $this->loadFollowers();
    }

    public function unfollow($userId)
    {
        Auth::user()->followingsUsers()->detach($userId);
        $this->loadFollowings();
        $this->loadFollowers();
    }

    public function render()
    {
        return view('livewire.community.following-followers-list');
    }
}
