<?php

namespace App\Livewire\UserProfile;

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class FollowerStats extends Component
{
    public User $profileUser;
    public bool $modalOpen = false;
    public string $activeTab = 'followers';
    public Collection $followers;
    public Collection $followings;

    protected $listeners = ['profile-follow-toggled' => 'refreshData'];

    public function mount(User $profileUser): void
    {
        $this->profileUser = $profileUser;
        $this->refreshData();
    }

    public function refreshData(): void
    {
        $this->followers = $this->profileUser->followersUsers()
            ->where('role', '!=', 'admin')
            ->with('ward.province')
            ->get();

        $this->followings = $this->profileUser->followingsUsers()
            ->where('role', '!=', 'admin')
            ->with('ward.province')
            ->get();
    }

    public function openModal(string $tab = 'followers'): void
    {
        $this->activeTab = $tab === 'following' ? 'following' : 'followers';
        $this->modalOpen = true;
    }

    public function closeModal(): void
    {
        $this->modalOpen = false;
    }

    public function render()
    {
        return view('livewire.user-profile.follower-stats', [
            'followersCount' => $this->followers->count(),
            'followingsCount' => $this->followings->count(),
        ]);
    }
}
