<?php

namespace App\Livewire\Public;

use App\Models\Challenge;
use App\Models\Habit;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $search = '';
    public $category = 'all';
    public $province_id;
    public $ward_id;
    public $provinces;
    public $wards = [];
    public $allWards;
    public $joinedChallenges = [];
    public $joinedHabits = [];

    public $perPage = 6;
    public $searched = false;

    public function mount()
    {
        $user = auth()->user();

        if ($user) {
        $this->joinedChallenges = $user->joinedChallenges()->pluck('challenge_id')->toArray();
        $this->joinedHabits = $user->joinedHabits()->pluck('habit_id')->toArray();
        }
        $this->provinces = Province::orderBy('name')->get();
        $this->allWards  = Ward::orderBy('name')->get();
        $this->wards     = $this->allWards;
    }

    public function updatedProvinceId($value)
    {
        $this->ward_id = '';
        $this->wards = Ward::where('province_id', $value)->orderBy('name')->get();
        $this->resetPage();
    }

    public function searchAction()
    {
        if (trim($this->search) === '' &&
        $this->category === 'all' &&
        $this->province_id === '' &&
        $this->ward_id === '') {
        session()->flash('error', 'Vui lòng nhập từ khóa hoặc lọc để tìm kiếm chính xác hơn.');
        return;
    }
        $this->searched = true;
        $this->resetPage();
    }

    public function toggleJoinItem($type, $id)
    {
        $user = auth()->user();
        if (!$user) {
            session()->flash('message', 'Bạn cần đăng nhập để tham gia.');
            return;
        }

        if ($type === 'challenge') {
            if (in_array($id, $this->joinedChallenges)) {
            //nếu đã tgia, bỏ tgia
                $user->joinedChallenges()->detach($id);
                $this->joinedChallenges = array_diff($this->joinedChallenges, [$id]);
            } else {
            //nếu ch tgia, tgia
                $user->joinedChallenges()->syncWithoutDetaching([$id]);
                $this->joinedChallenges[] = $id;
            }
        }

        if ($type === 'habit') {
            if (in_array($id, $this->joinedHabits)) {
                $user->joinedHabits()->detach($id);
                $this->joinedHabits = array_diff($this->joinedHabits, [$id]);
            } else {
            $user->joinedHabits()->syncWithoutDetaching([$id]);
            $this->joinedHabits[] = $id;
            }
        }
    }


    public function render()
    {
        $challenges = Challenge::query();
        $habits = Habit::query();

        if ($this->search) {
            $challenges->where('title', 'like', '%'.$this->search.'%');
            $habits->where('title', 'like', '%'.$this->search.'%');
        }

        // if ($this->province_id) {
        //     $challenges->where('province_id', $this->province_id);
        //     $habits->where('province_id', $this->province_id);
        // }
        if ($this->province_id) {
            $wardIds = Ward::where('province_id', $this->province_id)->pluck('id')->toArray();
            $challenges->whereIn('ward_id', $wardIds);
        }

        if ($this->ward_id) {
            $challenges->where('ward_id', $this->ward_id);
           //$habits->where('ward_id', $this->ward_id);
        }

        //lọc theo category
        if ($this->category === 'challenge') {
            $habits = collect([]);
            $challenges = $challenges->get();
        } elseif ($this->category === 'habit') {
            $challenges = collect([]);
            $habits = $habits->get();
        } else {
            $challenges = $challenges->get();
            $habits = $habits->get();
        }

        //gộp challenges,havit vào collection chung
        $items = collect([]);
        foreach ($challenges as $c) {
            $c->type = 'challenge';
            $items->push($c);
        }

        foreach ($habits as $h) {
            $h->type = 'habit';
            $items->push($h);
        }

        //sx theo id mới nhất
        $items = $items->sortByDesc('id')->values();

        //tự tạo phân trang
        $currentPage = $this->page ?? 1;

        $paged = new LengthAwarePaginator(
            $items->forPage($currentPage, $this->perPage),
            $items->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        return view('livewire.public.listing', [
            'items' => $paged,
        ])->layout('layouts.app');
    }
}

