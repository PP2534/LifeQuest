<?php

namespace App\Livewire\Challenges; // Hãy chắc chắn namespace của bạn là 'Challenges' (số nhiều)

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChallengeDetail extends Component
{
    public Challenge $challenge;
    public string $newComment = '';

    /**
     * Biến này sẽ lưu thông tin tham gia (tiến độ, chuỗi,...)
     * của CHỈNG NGƯỜI DÙNG đang xem.
     */
    public ?ChallengeParticipant $myParticipation = null;

    /**
     * Mount component, tải dữ liệu thử thách từ route
     */
    public function mount(Challenge $challenge)
    {
        // Tải thử thách cùng các mối quan hệ để tối ưu query
        $this->challenge = $challenge->load(
            'creator',                // Lấy người tạo (User)
            'categories',             // Lấy danh mục
            'participants.user',      // Lấy TẤT CẢ người tham gia VÀ thông tin user của họ
            'comments.user'           // Lấy TẤT CẢ bình luận VÀ thông tin user của họ
        );
        
        // Tải thông tin tham gia của riêng người dùng này
        $this->loadMyParticipation();
    }

    /**
     * Tải thông tin tham gia của người dùng hiện tại
     */
    public function loadMyParticipation()
    {
        if (Auth::check()) {
            // Lấy từ collection đã load ở mount() để tiết kiệm query
            $this->myParticipation = $this->challenge->participants
                ->where('user_id', Auth::id())
                ->first();
        }
    }

    /**
     * Xử lý khi người dùng tham gia thử thách
     */
    public function joinChallenge()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        // Kiểm tra lại nếu chưa tham gia
        if (!$this->myParticipation) {
            ChallengeParticipant::create([
                'challenge_id' => $this->challenge->id,
                'user_id' => Auth::id(),
                'role' => 'member',
                'status' => 'active',
                'personal_start_date' => now(),
                'progress_percent' => 0, // Khởi tạo tiến độ
                'streak' => 0,           // Khởi tạo chuỗi
            ]);

            // Tải lại dữ liệu (vì collection đã thay đổi)
            $this->challenge->refresh()->load('participants.user', 'comments.user', 'creator', 'categories');
            $this->loadMyParticipation(); // Tải lại thông tin của tôi sau khi refresh
            
            session()->flash('success', 'Bạn đã tham gia thử thách thành công!');
        }
    }

    /**
     * Xử lý khi người dùng rời thử thách
     */
    public function leaveChallenge()
    {
        if ($this->myParticipation) {
            $this->myParticipation->delete(); // Xóa record tham gia
            
            $this->challenge->refresh()->load('participants.user', 'comments.user', 'creator', 'categories'); // Tải lại
            $this->myParticipation = null; // Reset
            
            session()->flash('info', 'Bạn đã rời khỏi thử thách.');
        }
    }

    /**
     * Thêm bình luận mới
     */
    public function addComment()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->validate([
            'newComment' => 'required|string|min:3|max:1000',
        ]);

        Comment::create([
            'challenge_id' => $this->challenge->id,
            'user_id' => Auth::id(),
            'content' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->challenge->refresh()->load('comments.user', 'participants.user', 'creator', 'categories'); // Tải lại toàn bộ
        
        session()->flash('success', 'Đã đăng bình luận!');
    }

    /**
     * Hiển thị view
     */
    public function render()
    {
        // Logic Bảng xếp hạng: Sắp xếp người tham gia
        // dựa trên 'progress_percent' từ cao đến thấp
        $leaderboard = $this->challenge->participants->sortByDesc('progress_percent');

        return view('livewire.challenges.challenge-detail', [
            'isParticipant' => !is_null($this->myParticipation),
            'leaderboard' => $leaderboard,
        ])->layout('layouts.app');
    }
}