<?php

namespace App\Livewire\UserProfile;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Follower;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Create extends Component
{
    use WithPagination;

    public $search = '';
    public $interest = '';
    public $province_id = '';
    public $provinces;
    public $ward_id = '';
    public $wards = [];
    public $errorMessage = '';

     protected $updatesQueryString = ['search', 'interest', 'province_id', 'ward_id'];

    public function mount()
    {
        // $this->provinces = Province::all();
        //  if (request()->has('page') && request('page') > 1) {
        // $this->resetPage();
        $this->provinces = Province::all();
        $this->resetPageIfInvalid();
    }

    // Reset phân trang khi search/filter thay đổi
    public function updatingSearch() { $this->resetPage(); }
    public function updatingInterest() { $this->resetPage(); }
    public function updatingProvinceId() {
        $this->ward_id = '';
        $this->wards = $this->province_id ? Ward::where('province_id', $this->province_id)->get() : [];
        $this->resetPage();
    }

    public function updatingWardId() { 
        $this->resetPage();  
    }

    // public function loadWards()
    // {
    //     $this->wards = $this->province_id 
    //         ? Ward::where('province_id', $this->province_id)->get() 
    //         : [];
    // }

     private function resetPageIfInvalid()
    {
        $query = User::where('id', '!=', Auth::id());
        $total = $query->count();
        $perPage = 12;
        $lastPage = ceil($total / $perPage);

        if (request()->has('page') && request('page') > $lastPage) {
            $this->resetPage();
        }
    }

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

        if (!empty(trim($this->search))) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty(trim($this->interest))) {
            $query->where('interests', 'like', '%' . $this->interest . '%');
        }

        if (!empty($this->province_id)) {
            $query->whereHas('ward.province', function($q) {
                $q->where('id', $this->province_id);
            });
        }

        if (!empty($this->ward_id)) {
            $query->whereHas('ward', function ($q) {
            $q->where('id', $this->ward_id);
        });
       
        }

        // $users = $query->paginate(12);
    $perPage = 12;
    $users = $query->paginate($perPage);

    // Livewire sẽ reset page nếu page hiện tại > lastPage
    if ($users->lastPage() <= 1) {
        $this->resetPage();
    }
    
    if ($users->isEmpty()) {
            $this->errorMessage = !empty(trim($this->search)) 
                ? 'Không có người dùng nào tên "' . $this->search . '" được tìm thấy.'
                : 'Không tìm thấy người dùng phù hợp với bộ lọc của bạn.';
        } else {
            $this->errorMessage = '';
        }

        // Nếu tỉnh được chọn mà chưa load wards, load ngay
        if ($this->province_id && empty($this->wards)) {
            $this->loadWards();
        }

       // $provinces = Province::all();

        //return view('livewire.user-profile.create', compact('users'));
    
        return view('livewire.user-profile.create', [
        'users' => $users,
        'provinces' => Province::all(),
        'wards' => $this->wards
    ]);
    }
}
