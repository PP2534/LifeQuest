<?php

namespace App\Livewire\UserProfile;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Follower;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class Create extends Component
{
    use WithPagination;

    public $search = '';
    public $interest = '';
    public $province_id = '';
    public $ward_id = '';
    public $provinces;
    public $wards = [];
    public $allWards;
    public $perPage = 12;
    public $searched = false;
    public $errorMessage = '';
    public $followedUsers = [];

    protected $updatesQueryString = ['search', 'interest', 'province_id', 'ward_id'];

    public function mount()
    {
        $user = auth()->user();
        if ($user) {
            $this->followedUsers = $user->followings()->pluck('following_id')->toArray();
        }
        $this->provinces = Province::orderBy('name')->get();
        $this->allWards = Ward::orderBy('name')->get();
        $this->wards = $this->allWards;
    }

    public function updatedProvinceId($value)
    {
        $this->ward_id = '';
        $this->wards = $value ? Ward::where('province_id', $value)->orderBy('name')->get() : $this->allWards;
        $this->resetPage();
        $this->searched = true;
    }

    public function searchAction()
    {
    //TH:không nhập gì,vẫn tìm nhưng hiện thông báo
    if (
        trim($this->search) === '' &&
        $this->interest === '' &&
        $this->province_id === '' &&
        $this->ward_id === ''
    ) {
        session()->flash('warning', 'Vui lòng nhập từ khóa hoặc lọc để tìm kiếm chính xác hơn.');
    }

    $this->searched = true;
    $this->resetPage();
    }

    public function toggleFollow($userId)
    {
        $user = Auth::user();
        $existing = Follower::where(['follower_id' => $user->id,'following_id' => $userId])->first();

        if ($existing) {
            $existing->delete();
        } else {
            Follower::create(['follower_id' => $user->id,'following_id' => $userId]);
        }
    }

    public function render()
    {
        $query = User::with(['ward.province', 'followers'])->where('id', '!=', Auth::id());

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        if ($this->interest) {
            $query->where('interests', 'like', '%'.$this->interest.'%');
        }

        if ($this->province_id) {
            $wardIds = Ward::where('province_id', $this->province_id)->pluck('id')->toArray();
            $query->whereIn('ward_id', $wardIds);
        }

        if ($this->ward_id) {
            $query->where('ward_id', $this->ward_id);
        }

        $users = $query->paginate($this->perPage);

        return view('livewire.user-profile.create', [
            'users' => $users,
            'provinces' => $this->provinces,
            'wards' => $this->wards,
        ])->layout('layouts.app');
    }
}
