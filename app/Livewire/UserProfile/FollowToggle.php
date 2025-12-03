<?php

namespace App\Livewire\UserProfile;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowToggle extends Component
{
    public User $profileUser;
    public bool $isFollowing = false;
    public int $followersCount = 0;
    public bool $isOwnProfile = false;

    public function mount(User $profileUser): void
    {
        $this->profileUser = $profileUser;
        $this->isOwnProfile = Auth::check() && Auth::id() === $profileUser->id;
        $this->followersCount = $profileUser->followers()->count();

        if (Auth::check() && !$this->isOwnProfile) {
            $this->isFollowing = Auth::user()
                ->followings()
                ->where('following_id', $profileUser->id)
                ->exists();
        }
    }

    public function toggleFollow(): void
    {
        if (!Auth::check() || $this->isOwnProfile) {
            return;
        }

        $existingFollow = Follower::where([
            'follower_id' => Auth::id(),
            'following_id' => $this->profileUser->id,
        ])->first();

        if ($existingFollow) {
            $existingFollow->delete();
            $this->isFollowing = false;
            $this->followersCount = max(0, $this->followersCount - 1);
        } else {
            Follower::create([
                'follower_id' => Auth::id(),
                'following_id' => $this->profileUser->id,
            ]);
            $this->isFollowing = true;
            $this->followersCount++;
        }

        $this->dispatch('profile-follow-toggled', [
            'isFollowing' => $this->isFollowing,
            'followersCount' => $this->followersCount,
        ]);
    }

    public function render()
    {
        return view('livewire.user-profile.follow-toggle');
    }
}
