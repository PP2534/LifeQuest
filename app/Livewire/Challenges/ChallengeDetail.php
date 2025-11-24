<?php

namespace App\Livewire\Challenges; // Hãy chắc chắn namespace của bạn là 'Challenges' (số nhiều)

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Services\XpService;
use App\Models\ChallengeInvitation;
use Livewire\Attributes\Computed;

class ChallengeDetail extends Component
{
    public Challenge $challenge;
    public string $newComment = '';
    public $showInviteModal = false;

    /**
     * Biến này sẽ lưu thông tin tham gia (tiến độ, chuỗi,...)
     * của CHỈNG NGƯỜI DÙNG đang xem.
     */
    public ?ChallengeParticipant $myParticipation = null;

    // Biến lưu trữ lời mời đang chờ
    public ?ChallengeInvitation $pendingInvitation = null;

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
        //Kiểm tra xem có lời mời nào đang chờ cho user này ở challenge này không
        $this->checkPendingInvitation();
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
    public function joinChallenge(XpService $xpService)
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

            // Kiểm tra và cộng điểm cho người tạo challenge khi có người tham gia mới
            $xpService->awardCreatorChallengeMilestoneXp($this->challenge);

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
     * Chức năng kick mấy người tham gia khó ưu
     */
    public function kickMember($participantId)
    {
        // 1. Chỉ người tạo (Creator) mới được quyền kick
        if ($this->challenge->creator_id !== Auth::id()) {
            return;
        }

        $participant = ChallengeParticipant::find($participantId);

        // 2. Không thể kick chính mình
        if ($participant && $participant->user_id !== Auth::id()) {
            $participant->update(['status' => 'kicked']);
            
            // Tải lại danh sách để cập nhật giao diện
            $this->challenge->load('participants.user');
            session()->flash('success', 'Đã khóa thành viên này.');
        }
    }
      /**
     * Chức năng kick mấy người tham gia hết khó ưu
     */
    public function restoreMember($participantId)
    {
        // 1. Chỉ người tạo mới được quyền mở
        if ($this->challenge->creator_id !== Auth::id()) {
            return;
        }

        $participant = \App\Models\ChallengeParticipant::find($participantId);

        if ($participant) {
            $participant->update(['status' => 'active']);
            
            // Tải lại danh sách
            $this->challenge->load('participants.user');
            session()->flash('success', 'Đã mở khóa cho thành viên này.');
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
#[Computed]
    public function followings()
    {
        if (!Auth::check()) {
            return collect();
        }

        $challengeId = $this->challenge->id;

        
        return Auth::user()->followingsUsers()->get()->map(function ($user) use ($challengeId) {
            
            //  Kiểm tra đã tham gia chưa (Ưu tiên cao nhất)
            $isParticipant = ChallengeParticipant::where('challenge_id', $challengeId)
                ->where('user_id', $user->id)
                ->exists();

            if ($isParticipant) {
                $user->invite_status = 'active'; 
                return $user;
            }

            //  Kiểm tra trạng thái lời mời mới nhất
            $invitation = ChallengeInvitation::where('challenge_id', $challengeId)
                ->where('invitee_id', $user->id)
                ->latest()
                ->first();

            if ($invitation) {
                // Trả về: 'pending', 'rejected', hoặc 'accepted'
                $user->invite_status = $invitation->status;
            } else {
                // Chưa có lời mời nào
                $user->invite_status = 'none';
            }

            return $user;
        });
    }

    /**
     * Hàm này là mở Modal
     */
    public function openInviteModal()
    {
        if (Auth::check()) {
            $this->showInviteModal = true;
        }
    }
    /**
     * Gửi lời mời
     */
    public function inviteUser($userId)
    {
        ChallengeInvitation::create([
            'challenge_id' => $this->challenge->id,
            'inviter_id' => Auth::id(),
            'invitee_id' => $userId,
            'status' => 'pending'
        ]);

        // Reload lại danh sách để cập nhật nút bấm
        $this->openInviteModal();
        session()->flash('success', 'Đã gửi lời mời thành công!');
    }
    /**
     * Kiểm tra lời mời đang chờ
     */
    public function checkPendingInvitation()
    {
        if (Auth::check() && !$this->myParticipation) {
            $this->pendingInvitation = ChallengeInvitation::where('challenge_id', $this->challenge->id)
                ->where('invitee_id', Auth::id())
                ->where('status', 'pending')
                ->with('inviter') // Tải thông tin người mời để hiển thị tên
                ->first();
        }
    }

    /**
     * Xử lý Chấp nhận lời mời
     */
    public function acceptInvitation()
    {
        if ($this->pendingInvitation) {
            // Cập nhật trạng thái lời mời thành 'accepted'
            $this->pendingInvitation->update(['status' => 'accepted']);

            // Thêm người dùng vào danh sách tham gia (như nút Join bình thường)
            ChallengeParticipant::create([
                'challenge_id' => $this->challenge->id,
                'user_id' => Auth::id(),
                'role' => 'member',
                'status' => 'active',
                'personal_start_date' => now(),
                'progress_percent' => 0,
                'streak' => 0,
            ]);

            // Reset biến để tắt Popup
            $this->pendingInvitation = null;

            // Tải lại dữ liệu trang
            $this->challenge->load('participants.user');
            $this->loadMyParticipation();

            session()->flash('success', 'Tuyệt vời! Bạn đã chấp nhận lời mời tham gia.');
        }
    }

    /**
     * Xử lý Từ chối lời mời
     */
    public function rejectInvitation()
    {
        if ($this->pendingInvitation) {
            // 1. Cập nhật trạng thái lời mời thành 'rejected'
            $this->pendingInvitation->update(['status' => 'rejected']);

            // Reset biến để tắt Popup
            $this->pendingInvitation = null;

            session()->flash('info', 'Bạn đã từ chối lời mời.');
        }
    }
    /**
     * Hiển thị view
     */
    public function render()
    {
        // Logic Bảng xếp hạng: Sắp xếp người tham gia
        // dựa trên 'progress_percent' từ cao đến thấp
        $leaderboard = $this->challenge->participants->sortByDesc('progress_percent');
        // Logic kiểm tra quyền hiển thị nút Mời
        $canInvite = false;
        if (Auth::check()) {
            $isCreator = $this->challenge->creator_id == Auth::id();
            // Điều kiện: Là người tạo HOẶC (Là thành viên VÀ thử thách cho phép thành viên mời)
            $canInvite = $isCreator || ($this->myParticipation && $this->challenge->allow_member_invite);
        }

        return view('livewire.challenges.challenge-detail', [
            'isParticipant' => !is_null($this->myParticipation),
            'leaderboard' => $leaderboard,
            'canInvite' => $canInvite,
        ])->layout('layouts.app');
    }
}