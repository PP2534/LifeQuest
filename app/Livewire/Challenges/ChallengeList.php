<?php

namespace App\Livewire\Challenges;

use Livewire\Component;
use App\Models\Challenge;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class ChallengeList extends Component
{
    use WithPagination;
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
            ->withCount('participants')
            ->latest() // Sắp xếp mới nhất
            ->paginate(3);

        return view('livewire.challenges.challenge-list', [
            'challenges' => $challenges,
        ])->layout('layouts.app');
    }
}
