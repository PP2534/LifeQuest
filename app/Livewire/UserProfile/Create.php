<?php

namespace App\Livewire\UserProfile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Create extends Component
{
    use WithPagination;

    public $search = '';
    public $interest = '';
    public $province_id = '';
    public $errorMessage = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingInterest() { $this->resetPage(); }
    public function updatingProvinceId() { $this->resetPage(); }

   #[Layout('layouts.app')]
    public function render()
    {
        $query = User::query()->with(['ward.province', 'followers'])
                    ->where('id', '!=', Auth::id());

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        if ($this->interest) {
            $query->where('interests', 'like', '%' . $this->interest . '%');
        }

       if ($this->province_id) {
            $query->whereHas('ward.province', function ($q) {
                $q->where('id', $this->province_id);
            });
        }

        $users = $query->paginate(9);

        $this->errorMessage = ($this->search && $users->isEmpty())
            ? 'Không có người dùng nào tên "' . $this->search . '" được tìm thấy.'
            : '';

        return view('livewire.user-profile.create',compact('users'));
    }
}
