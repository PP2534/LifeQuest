<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Challenge;
use App\Models\Habit;
use App\Models\Province;
use App\Models\Ward;

class Create extends Component
{
    public $searchAll = '';
    public $category = 'all';
    public $province_id = '';
    public $ward_id = '';
    public $wards;
    public $provinces;
    public $searched = false;
    public $perPage = 6;

    public function mount()
    {
        $this->provinces = Province::orderBy('name')->get();
        $this->wards = collect(); // luôn là Collection
    }

    public function updatedProvinceId($value)
    {
        $this->ward_id = ''; // reset phường khi đổi tỉnh

        if ($value) {
            $this->wards = Ward::where('province_id', $value)
                               ->orderBy('name')
                               ->get(['id', 'name']);
        } else {
            $this->wards = collect(); // clear wards nếu tỉnh không chọn
        }
    }

    public function search()
    {
        $this->searched = true;
    }

    public function render()
    {
        $challenges = Challenge::query();
        $habits = Habit::query();

        if ($this->searchAll) {
            $challenges->where('title', 'like', "%{$this->searchAll}%");
            $habits->where('title', 'like', "%{$this->searchAll}%");
        }

        if ($this->category === 'challenge') $habits->whereRaw('1=0');
        if ($this->category === 'habit') $challenges->whereRaw('1=0');

        if ($this->ward_id) {
            $challenges->where('ward_id', $this->ward_id);
            $habits->where('ward_id', $this->ward_id);
        }

        return view('livewire.public.create', [
            'challenges' => $challenges->paginate($this->perPage),
            'habits'     => $habits->paginate($this->perPage),
            'provinces'  => $this->provinces,
            'wards'      => $this->wards,
        ])->layout('layouts.app');
    }
}
