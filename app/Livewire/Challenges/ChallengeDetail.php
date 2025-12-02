<?php

namespace App\Livewire\Challenges; // Hãy chắc chắn namespace của bạn là 'Challenges' (số nhiều)

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use App\Services\XpService;
use App\Models\ChallengeInvitation;
use Livewire\Attributes\Computed;
use Carbon\Carbon;


class ChallengeDetail extends Component
{
    public Challenge $challenge;
    public string $newComment = '';
    public int $commentsLimit = 10;
    public int $commentsCount = 0;

    public $showInviteModal = false;

    /**
     * Biến này sẽ lưu thông tin tham gia (tiến độ, chuỗi,...)
     * của CHỈNG NGƯỜI DÙNG đang xem.
     */
    public ?ChallengeParticipant $myParticipation = null;

    // Biến lưu trữ lời mời đang chờ
    public ?ChallengeInvitation $pendingInvitation = null;

    //Biến cho cài đặt thời gian
    public $showDateModal = false;
    public $newStartDate;

    /**
     * Mount component, tải dữ liệu thử thách từ route
     */
    public function mount(Challenge $challenge)
    {
        // Tải thử thách cùng các mối quan hệ để tối ưu query
        $this->challenge = $challenge->load(
            'creator',                // Lấy người tạo (User)
            'categories',             // Lấy danh mục
            'participants.user'      // Lấy TẤT CẢ người tham gia VÀ thông tin user của họ
        );
        
        // Tải thông tin tham gia của riêng người dùng này
        $this->loadMyParticipation();
        // Đếm tổng số bình luận
        $this->commentsCount = $this->challenge->comments()->count();
        //Kiểm tra xem có lời mời nào đang chờ cho user này ở challenge này không
        $this->checkPendingInvitation();

        // // Khởi tạo giá trị cho input ngày 
        if ($this->challenge->start_date) {
            $this->newStartDate = Carbon::parse($this->challenge->start_date)
        ->format('Y-m-d\TH:i');
        }
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

        // Không cho phép tham gia nếu thử thách đã khóa hiển thị
        if ($this->isChallengeDisplayLocked) {
            session()->flash('info', 'Thử thách này đã kết thúc hoặc bị khóa, không thể tham gia.');
            return;
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
            
            $this->challenge->refresh()->load('participants.user', 'creator', 'categories'); // Tải lại, không cần comments
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
        if ((int)$this->challenge->creator_id !== Auth::id()) {
            return;
        }

        $participant = ChallengeParticipant::find($participantId);

        // 2. Không thể kick chính mình
        if ((int)$participant && $participant->user_id !== Auth::id()) {
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
        if ((int)$this->challenge->creator_id !== Auth::id()) {
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
            'newComment' => 'string|min:1|max:1000',
        ]);

        // Quy trình làm sạch và kiểm tra nội dung bình luận từ Trix Editor:
        // 1. strip_tags: Loại bỏ các thẻ HTML (<div>, <br>, ...).
        // 2. str_replace: Chuyển các ký tự space đặc biệt (&nbsp;) thành space thường.
        // 3. trim: Loại bỏ khoảng trắng, tab, xuống dòng ở đầu và cuối.
        $cleanedComment = trim(str_replace('&nbsp;', ' ', strip_tags($this->newComment)));

        if (empty($cleanedComment)) {
            $this->addError('newComment', 'Nội dung bình luận không được để trống.');
            return; // Dừng thực thi nếu bình luận rỗng sau khi làm sạch
        }

        Comment::create([
            'challenge_id' => $this->challenge->id,
            'user_id' => Auth::id(),
            'content' => $this->newComment,
        ]);

        $this->newComment = '';
        // Không cần refresh toàn bộ challenge, chỉ cần cập nhật số lượng bình luận
        $this->commentsCount++;
        $this->reset('newComment');
        $this->dispatch('trix-clear');
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

        // [QUAN TRỌNG] Xóa cache của computed property
        // Để lần render tới, nó sẽ tự tính lại và thấy trạng thái mới là 'pending'
        unset($this->followings); 

        // Mở lại modal (để người dùng thấy trạng thái mới)
        $this->showInviteModal = true; 
        
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
#[Computed]
    public function isLocked()
    {
        // Nếu là Rolling (Linh hoạt) -> Không bao giờ khóa
        if ($this->challenge->time_mode === 'rolling') {
            return false;
        }

        // Nếu là Fixed (Cố định):
        // Chưa set ngày bắt đầu -> Chưa khóa (để người ta còn join)
        if (!$this->challenge->start_date) {
            return false;
        }

        // Nếu Hiện tại > Ngày bắt đầu -> KHÓA (Ẩn nút join, invite, checkin...)
        return now()->gt($this->challenge->start_date);
    }

    #[Computed]
    public function endDate()
    {
        if (!$this->challenge->start_date || !$this->challenge->duration_days) {
            return null;
        }
        return Carbon::parse($this->challenge->start_date)->addDays((int)$this->challenge->duration_days);
    }

    #[Computed]
    public function isEnded()
    {
        if (!$this->endDate) {
            return false;
        }
        return now()->gt($this->endDate);
    }
    
    #[Computed]
    public function lockedMessage()
    {
        if ($this->isEnded) {
            return "Thử thách đã kết thúc";
        }

        // Chỉ các thử thách cố định mới có thể ở trạng thái "đang diễn ra" và bị khóa trước khi kết thúc
        if ($this->challenge->time_mode === 'fixed' && $this->isLocked) {
            return "Thử thách đang diễn ra";
        }

        return ''; // Không có thông báo nếu chưa kết thúc và không phải thử thách cố định đã bị khóa
    }

    #[Computed]
    public function isChallengeDisplayLocked()
    {
        // Fixed challenges are locked based on their start date
        if ($this->challenge->time_mode === 'fixed') {
            return $this->isLocked;
        }

        // Rolling challenges are "locked" for display purposes if they have ended
        return $this->isEnded;
    }

    /**
     * Lưu thời gian bắt đầu (Chỉ Creator)
     */
    public function setStartDate()
    {
        if ((int)$this->challenge->creator_id !== Auth::id()) {
            abort(403);
        }

        $this->validate([
            'newStartDate' => 'required|date|after:now', // Phải là ngày trong tương lai
        ], [
            'newStartDate.after' => 'Thời gian bắt đầu phải ở trong tương lai để đếm ngược!',
        ]);

        $this->challenge->update([
            'start_date' => $this->newStartDate
        ]);

        $this->showDateModal = false;
        session()->flash('success', 'Đã đặt lịch bắt đầu thử thách!');
    }
    /**
     * Tải thêm bình luận
     */
    public function loadMoreComments()
    {
        $this->commentsLimit += 10;
    }

     public function completedUpload(string $uploadedFilename, $attachment)
    {
        // Lưu file và lấy URL
        $url = Storage::url($this->newCommentAttachment->store('attachments', 'public'));

        // Gửi sự kiện về client để chèn ảnh vào Trix
        $this->dispatch('trix-attachment-upload-completed', [
            'url' => $url,
            'href' => $url,
            'attachment' => $attachment
        ]);
    }

    /**
     * Xóa một bình luận.
     */
    public function deleteComment(Comment $comment)
    {
        // Sử dụng policy để kiểm tra quyền hạn
        // Điều này sẽ tự động kiểm tra xem người dùng có phải là chủ sở hữu
        // hoặc có vai trò admin hay không (dựa vào CommentPolicy).
        $this->authorize('delete', $comment);

        // Xóa bình luận
        $comment->delete();

        // Giảm số lượng bình luận
        $this->commentsCount--;

        // Gửi thông báo thành công (flash message)
        session()->flash('success', 'Đã xóa bình luận thành công.');
    }

    /**
     * Thuộc tính tính toán (computed property) để lấy danh sách bình luận.
     * Nó sẽ tự động được gọi lại mỗi khi component render.
     */
    public function getCommentsProperty()
    {
        return $this->challenge
            ->comments()
            ->with('user') // Eager load thông tin người dùng
            ->latest() // Sắp xếp theo thứ tự mới nhất
            ->take($this->commentsLimit) // Giới hạn số lượng
            ->get()
            ;
    }


    /**
     * Hiển thị view
     */
    public function render()
    {
        // Logic Bảng xếp hạng: Sắp xếp người tham gia
        // dựa trên 'progress_percent' từ cao đến thấp
        $leaderboard = $this->challenge->participants->sortByDesc('progress_percent');
        // Logic kiểm tra quyền hiển thị nút Mời.
        $canInvite = false;
        if (Auth::check()) {
            $isCreator = (int)$this->challenge->creator_id === Auth::id();
            // Điều kiện: Là người tạo HOẶC (Là thành viên VÀ thử thách cho phép thành viên mời)
            $canInvite = !$this->isChallengeDisplayLocked && ($isCreator || ($this->myParticipation && $this->challenge->allow_member_invite));
        }

        return view('livewire.challenges.challenge-detail', [
            'isParticipant' => !is_null($this->myParticipation),
            'leaderboard' => $leaderboard,
            'comments' => $this->comments, // Sử dụng computed property
            'canInvite' => $canInvite,
        ])->layout('layouts.app');
    }
}