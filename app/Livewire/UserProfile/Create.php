<?php

namespace App\Livewire\UserProfile;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Follower;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use WithPagination;

    public $search = '';
    public $interest = '';
    public $province_id = '';
    public $errorMessage = '';

    // Reset phân trang khi search/filter thay đổi
    public function updatingSearch() { $this->resetPage(); }
    public function updatingInterest() { $this->resetPage(); }
    public function updatingProvinceId() { $this->resetPage(); }

    // Toggle follow/unfollow
    public function toggleFollow($userId)
    {
        $existingFollow = Follower::where([
            'follower_id' => Auth::id(),
            'following_id' => $userId
        ])->first();

        if ($existingFollow) {
            $existingFollow->delete();
        } else {
            Follower::create([
                'follower_id' => Auth::id(),
                'following_id' => $userId
            ]);
        }
    }

    public function render()
    {
        $query = User::with(['ward.province', 'followers'])
            ->where('id', '!=', Auth::id());

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->interest) {
            $query->where('interests', 'like', '%' . $this->interest . '%');
        }

        if ($this->province_id) {
            $query->whereHas('ward.province', fn($q) => $q->where('id', $this->province_id));
        }

        $users = $query->paginate(9);

        $this->errorMessage = ($this->search && $users->isEmpty()) 
            ? 'Không có người dùng nào tên "' . $this->search . '" được tìm thấy.'
            : '';

        return view('livewire.user-profile.create', compact('users'));
    }
}
