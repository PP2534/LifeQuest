<?php

namespace App\Livewire\Public;

use App\Models\Challenge;
use App\Models\Habit;
use App\Models\Province;
use App\Models\Ward;
use Livewire\Component;
use Livewire\WithPagination;

class Create extends Component
{
use WithPagination;

    public $search = '';
    public $category = 'all'; // all, challenge, habit
    public $province_id = '';
    public $ward_id = '';
    public $perPage = 6;
    public $searched = false;

    protected $queryString = ['search', 'category', 'province_id', 'ward_id'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategory() { $this->resetPage(); }
    public function updatingProvinceId() { 
        $this->resetPage();
        $this->ward_id = ''; // reset ward khi province thay đổi
    }
    public function updatingWardId() { $this->resetPage(); }

    // Computed property cho wards thuộc province đã chọn
    public function getWardsProperty()
    {
        if ($this->province_id) {
            return Ward::where('province_id', $this->province_id)->get();
        }
        return collect();
    }

    // Khi nhấn nút tìm kiếm
    public function search()
    {
        $this->searched = true;
        $this->resetPage();
    }

    public function render()
    {
        // Query challenges & habits
        $challenges = Challenge::query();
        $habits = Habit::query();

        // Search filter
        if (!empty($this->search)) {
            $challenges->where('name', 'like', "%{$this->search}%");
            $habits->where('name', 'like', "%{$this->search}%");
        }

        // Category filter
        if ($this->category === 'challenge') {
            $habits->whereRaw('1=0'); // hide habits
        } elseif ($this->category === 'habit') {
            $challenges->whereRaw('1=0'); // hide challenges
        }

        // Province filter
        if (!empty($this->province_id)) {
            $challenges->where('province_id', $this->province_id);
            $habits->where('province_id', $this->province_id);
        }

        // Ward filter
        if (!empty($this->ward_id)) {
            $challenges->where('ward_id', $this->ward_id);
            $habits->where('ward_id', $this->ward_id);
        }

        $provinces = Province::all();

        return view('livewire.public.create', [
            'challenges' => $challenges->paginate($this->perPage),
            'habits' => $habits->paginate($this->perPage),
            'provinces' => $provinces,
            'wards' => $this->wards,
        ])->layout('layouts.app');
    }
}