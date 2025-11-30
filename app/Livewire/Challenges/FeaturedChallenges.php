<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;


class FeaturedChallenges extends Component
{
    public Collection $challenges;

    /**
     * Mount the component and fetch the featured challenges.
     */
    public function mount(): void
    {
        $this->challenges = Cache::remember('users', 600,fn()=> Challenge::with('categories')
            ->withCount('participants')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->where('allow_request_join', true)
            ->orderByDesc('participants_count')
            ->take(6)
            ->get());
    }

    public function render()
    {
         $challenges = Challenge::with('categories')
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
            ->latest() // Sắp xếp mới nhất
            ->paginate(3);
        return view('livewire.challenges.featured-challenges');
    }
}