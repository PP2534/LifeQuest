<?php

namespace App\Livewire\Challenges; // Hãy chắc chắn namespace của bạn là 'Challenges' (số nhiều)

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ChallengeDetail extends Component
{
    public Challenge $challenge;
    public string $newComment = '';
    public int $commentsLimit = 10;
    public int $commentsCount = 0;


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
            'participants.user'      // Lấy TẤT CẢ người tham gia VÀ thông tin user của họ
        );
        
        // Tải thông tin tham gia của riêng người dùng này
        $this->loadMyParticipation();
        // Đếm tổng số bình luận
        $this->commentsCount = $this->challenge->comments()->count();
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
            
            $this->challenge->refresh()->load('participants.user', 'creator', 'categories'); // Tải lại, không cần comments
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

        return view('livewire.challenges.challenge-detail', [
            'isParticipant' => !is_null($this->myParticipation),
            'leaderboard' => $leaderboard,
            'comments' => $this->comments, // Sử dụng computed property
        ])->layout('layouts.app');
    }
}