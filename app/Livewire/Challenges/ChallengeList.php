<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class ChallengeList extends Component
{
    use WithPagination;

    public string $search = '';

    // Thuộc tính cho bộ lọc địa điểm
    public $selectedProvince = null;
    public $selectedWard = null;

    public Collection $provinces;
    public Collection $wards;

    public function mount()
    {
        $this->provinces = Province::orderBy('name')->get();
        $this->wards = collect();
    }

    public function updatedSelectedProvince($provinceId)
    {
        $this->wards = !empty($provinceId) ? Ward::where('province_id', $provinceId)->orderBy('name')->get() : collect();
        $this->selectedWard = null; // Reset phường/xã khi tỉnh thay đổi
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $challenges = Challenge::with('categories')
            ->active()
            ->where(function($query) {
                // Luôn hiển thị các thử thách PUBLIC với mọi người
                $query->where('type', 'public');

                // Nếu người dùng đã đăng nhập, hiển thị thêm các thử thách PRIVATE của họ
                if (Auth::check()) {
                    $query->orWhere(function($subQuery) {
                        $subQuery->where('type', 'private')
                                 ->where(function($q) {
                                     // Hiển thị nếu mình là Người tạo
                                     $q->where('creator_id', Auth::id())
                                       // Hoặc nếu mình là Người tham gia (Participant)
                                       ->orWhereHas('participants', function($p) {
                                           $p->where('user_id', Auth::id());
                                       });
                                 });
                    });
                }
            })
            ->where('title', 'like', '%' . $this->search . '%')
            ->when($this->selectedProvince, function ($query) {
                $query->whereHas('ward', function ($subQuery) {
                    $subQuery->where('province_id', $this->selectedProvince);
                });
            })
            ->when($this->selectedWard, function ($query) {
                $query->where('ward_id', $this->selectedWard);
            })
            ->withCount('participants')
            ->latest()
            ->paginate(12);

        return view('livewire.challenges.challenge-list', [
            'challenges' => $challenges,
        ])->layout('layouts.app');
    }
}
