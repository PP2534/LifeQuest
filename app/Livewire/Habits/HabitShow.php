<?php

namespace App\Livewire\Habits;

use App\Models\Habit;
use App\Models\HabitParticipant;
use Livewire\Component;
use App\Models\HabitInvitation;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HabitShow extends Component
{
    use WithFileUploads;

    public Habit $habit;
    public bool $isParticipant = false;
    public bool $isCreator = false;
    public ?string $participationStatus = null;
    public ?HabitParticipant $currentUserParticipant = null;
    public ?HabitInvitation $currentUserInvitation = null;

    public string $inviteEmail = '';

    public $year;
    public $month;
    public $monthName;

    public bool $showProofModal = false;
    public ?string $selectedDate = null;
    public $proofImage;

    public function mount(Habit $habit)
    {
        // Load all necessary relationships
        $this->habit = $habit->load(['participants.user', 'invitations.invitee', 'invitations.inviter']);

        $this->loadParticipationData();

        $now = Carbon::now();
        $this->year = $now->year;
        $this->month = $now->month;
        $this->updateMonthName();
    }
    
    public function requestToJoin()
    {
        if ($this->habit->type !== 'group' || $this->participationStatus !== null || !Auth::check()) {
            return;
        }

        // Create an invitation that represents a request to join
        HabitInvitation::create([
            'habit_id' => $this->habit->id,
            'inviter_id' => Auth::id(), // The user is inviting themselves
            'invitee_id' => Auth::id(),
            'status' => 'pending',
        ]);

        //  Nạp lại toàn bộ dữ liệu sau khi thay đổi
        $this->habit->refresh()->load(['participants.user', 'invitations.invitee', 'invitations.inviter']);
        $this->loadParticipationData();

        session()->flash('status', 'Yêu cầu tham gia của bạn đã được gửi đi và đang chờ duyệt.');
    }

    public function cancelRequest()
    {
        if (!$this->currentUserInvitation || $this->isCreator) {
            return;
        }

        $this->currentUserInvitation->delete();

        $this->habit->refresh()->load(['participants.user', 'invitations.invitee', 'invitations.inviter']);
        $this->loadParticipationData();

        session()->flash('status', 'Bạn đã hủy yêu cầu tham gia.');
    }

    public function leaveHabit()
    {
        if (!$this->isParticipant || $this->isCreator) {
            return;
        }

        HabitParticipant::where('habit_id', $this->habit->id)
            ->where('user_id', Auth::id())
            ->delete();

        // Làm mới dữ liệu sau khi rời nhóm
        $this->habit->refresh()->load(['participants.user']);
        $this->loadParticipationData();

        session()->flash('status', 'Bạn đã rời khỏi thói quen.');
    }

    public function deleteHabit()
    {
        // Chỉ người tạo mới có quyền xóa
        if (!$this->isCreator) {
            session()->flash('error', 'Bạn không có quyền xóa thói quen này.');
            return;
        }

        $this->habit->delete();

        // Chuyển hướng về trang danh sách với thông báo
        session()->flash('status', 'Thói quen đã được xóa thành công.');
        return redirect()->route('habits.index');
    }

    public function approveRequest(int $invitationId)
    {
        $invitation = HabitInvitation::find($invitationId);

        // 1. Kiểm tra xem lời mời có hợp lệ không
        if (!$invitation || $invitation->habit_id !== $this->habit->id || $invitation->status !== 'pending') {
            return;
        }

        // 2. Kiểm tra quyền
        $isAllowed = false;
        if ($this->isCreator) {
            // Người tạo có thể duyệt bất kỳ yêu cầu/lời mời nào
            $isAllowed = true;
        } elseif (Auth::check() && Auth::id() === $invitation->invitee_id) {
            // Người được mời chỉ có thể chấp nhận lời mời từ người khác
            // (không phải yêu cầu tự tham gia của chính họ)
            if ($invitation->inviter_id !== $invitation->invitee_id) {
                $isAllowed = true;
            }
        }

        if (!$isAllowed) {
            session()->flash('error', 'Bạn không có quyền thực hiện hành động này.');
            return;
        }

        // 3. Nếu được phép, tiến hành thêm thành viên
        HabitParticipant::create([
            'habit_id' => $this->habit->id,
            'user_id' => $invitation->invitee_id,
            'role' => 'member',
            'status' => 'active',
        ]);

        $invitation->delete();

        $this->habit->refresh()->load(['participants.user', 'invitations.invitee', 'invitations.inviter']);
        $this->loadParticipationData();
        session()->flash('status', 'Đã duyệt thành viên.');
    }

    public function rejectRequest(int $invitationId)
    {
        $invitation = HabitInvitation::find($invitationId);

        if (!$invitation || $invitation->habit_id !== $this->habit->id || $invitation->status !== 'pending') {
            return;
        }

        // Người tạo hoặc người được mời đều có thể từ chối/hủy
        $isAllowed = $this->isCreator || (Auth::check() && Auth::id() === $invitation->invitee_id);

        if (!$isAllowed) {
            session()->flash('error', 'Bạn không có quyền thực hiện hành động này.');
            return;
        }

        // Delete the invitation
        $invitation->delete();

        $this->habit->refresh()->load(['participants.user', 'invitations.invitee', 'invitations.inviter']);
        $this->loadParticipationData();
        session()->flash('status', 'Đã từ chối yêu cầu.');
    }

    public function inviteMember()
    {
        // Cho phép mọi thành viên mời nếu cài đặt được bật
        if (!$this->isParticipant || !$this->habit->allow_member_invite) {
            return;
        }

        $this->validate([
            'inviteEmail' => 'required|email|exists:users,email',
        ], [
            'inviteEmail.exists' => 'Không tìm thấy người dùng với email này.',
        ]);

        $invitee = User::where('email', $this->inviteEmail)->first();

        // Check if user is already a participant
        if ($this->habit->participants()->where('user_id', $invitee->id)->exists()) {
            session()->flash('invite_error', 'Người dùng này đã là thành viên.');
            return;
        }

        // Check if user already has a pending invitation
        if ($this->habit->invitations()->where('invitee_id', $invitee->id)->where('status', 'pending')->exists()) {
            session()->flash('invite_error', 'Người dùng này đã có lời mời đang chờ xử lý.');
            return;
        }

        HabitInvitation::create([
            'habit_id' => $this->habit->id,
            'inviter_id' => Auth::id(),
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);

        $this->habit->refresh()->load(['participants.user', 'invitations.invitee', 'invitations.inviter']);
        $this->inviteEmail = ''; // Clear input
        session()->flash('status', 'Đã gửi lời mời thành công.');
    }

    public function kickMember(int $participantId)
    {
        // Chỉ người tạo mới có quyền xóa thành viên
        if (!$this->isCreator) {
            return;
        }

        $participant = HabitParticipant::find($participantId);

        // Đảm bảo không xóa chính người tạo
        if ($participant && $participant->habit_id === $this->habit->id && $participant->role !== 'creator') {
            $participant->delete();
            $this->habit->refresh()->load(['participants.user', 'invitations.invitee', 'invitations.inviter']);
            $this->loadParticipationData();
            session()->flash('status', 'Đã xóa thành viên khỏi nhóm.');
        }
    }

    public function acceptInvitation()
    {
        if (!$this->currentUserInvitation || $this->currentUserInvitation->status !== 'pending') {
            return;
        }

        $this->approveRequest($this->currentUserInvitation->id);
        session()->flash('status', 'Bạn đã tham gia thói quen!');
    }

    public function rejectInvitation()
    {
        if (!$this->currentUserInvitation || $this->currentUserInvitation->status !== 'pending') {
            return;
        }

        $this->rejectRequest($this->currentUserInvitation->id);
        session()->flash('status', 'Bạn đã từ chối lời mời.');
    }

    protected function loadParticipationData(): void
    {
        // Reset state
        $this->isParticipant = false;
        $this->isCreator = false;
        $this->participationStatus = null;
        $this->currentUserParticipant = null;
        $this->currentUserInvitation = null;

        if (!Auth::check()) return;

        $userId = Auth::id();

        // 1. Check if user is already an active participant
        $this->currentUserParticipant = $this->habit->participants->firstWhere('user_id', $userId);

        if ($this->currentUserParticipant && $this->currentUserParticipant->status === 'active') {
            $this->isParticipant = true;
            $this->isCreator = $this->currentUserParticipant->role === 'creator';
            $this->participationStatus = 'participant';
            return;
        }

        // 2. If not an active participant, check for pending invitations
        $this->currentUserInvitation = $this->habit->invitations->where('invitee_id', $userId)->where('status', 'pending')->first();

        if ($this->currentUserInvitation) {
            $this->participationStatus = $this->currentUserInvitation->inviter_id === $userId ? 'pending_request' : 'invited';
        }
    }

    public function updateMonthName()
    {
        // Đảm bảo ngôn ngữ là tiếng Việt
        Carbon::setLocale('vi');
        $this->monthName = Carbon::create($this->year, $this->month)->translatedFormat('F Y');
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->updateMonthName();
        unset($this->calendarGrid); // Xóa cache để tính toán lại
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->updateMonthName();
        unset($this->calendarGrid); // Xóa cache để tính toán lại
    }

    public function toggleDayStatus(string $dateString)
    {
        if (!$this->isParticipant) return;

        $date = Carbon::parse($dateString);

        // Chỉ cho phép tương tác với ngày hôm nay
        if (!$date->isToday()) {
            return;
        }

        // Tìm log hiện có cho ngày hôm nay
        $log = \App\Models\HabitLog::where('habit_participant_id', $this->currentUserParticipant->id)
                                ->where('date', $date)
                                ->first();

        // Nếu đã hoàn thành, cho phép hủy (không cần modal)
        if ($log && $log->status === 'done') {
            $log->delete();
            unset($this->myLogs);
            session()->flash('status', 'Đã hủy ghi nhận.');
            $this->updateStreakForCurrentUser();
            return;
        }

        // Nếu chưa hoàn thành, tiến hành xử lý
        if ($this->habit->need_proof) {
            // Mở modal để yêu cầu bằng chứng
            $this->selectedDate = $dateString;
            $this->proofImage = null; // Reset ảnh cũ
            $this->showProofModal = true;
        } else {
            // Ghi nhận trực tiếp không cần bằng chứng
            $this->logDayAsDone($dateString);
            $this->updateStreakForCurrentUser();
            session()->flash('status', 'Đã ghi nhận hoàn thành!');
        }
    }

    public function saveLogWithProof()
    {
        if (!$this->selectedDate) return;

        $this->logDayAsDone($this->selectedDate);

        $this->updateStreakForCurrentUser();
        $this->closeProofModal();
        session()->flash('status', 'Đã ghi nhận hoàn thành!');
    }

    public function logDayAsDone(string $dateString, ?string $proofImagePath = null): void
    {
        \App\Models\HabitLog::create([
            'habit_participant_id' => $this->currentUserParticipant->id,
            'date' => Carbon::parse($dateString),
            'status' => 'done',
            'proof_image' => $proofImagePath,
        ]);
    }

    public function closeProofModal()
    {
        $this->showProofModal = false;
        $this->selectedDate = null;
        $this->proofImage = null;
        $this->resetErrorBag(); // Xóa lỗi validation (nếu có)
    }

    /**
     * Helper method to trigger streak calculation and UI refresh.
     */
    private function updateStreakForCurrentUser(): void
    {
        $this->currentUserParticipant?->calculateAndUpdateStreak();
        $this->currentUserParticipant?->refresh();
        unset($this->calendarGrid); // Force calendar to re-render with new data
    }

    #[Computed]
    public function myLogs()
    {
        if (!$this->currentUserParticipant) {
            return collect();
        }
        $freshParticipant = $this->currentUserParticipant->fresh(['logs']);

        if (!$freshParticipant) {
            return collect();
        }

        // Trả về logs với key là ngày tháng 'Y-m-d' để tra cứu nhanh
        return $freshParticipant->logs->keyBy(function ($log) {
            // Biện pháp phòng ngừa: Đảm bảo $log->date luôn là một đối tượng Carbon.
            // Livewire đôi khi có thể "làm khô" (dehydrate) các đối tượng Carbon thành chuỗi
            // và không "hồi sinh" (rehydrate) chúng một cách chính xác, đặc biệt với caching.
            $date = $log->date instanceof Carbon ? $log->date : Carbon::parse($log->date);
            return $date->format('Y-m-d');
        });
    }

    #[Computed]
    public function pendingInvitations(): Collection
    {
        if (!$this->isCreator) {
            return collect();
        }
        return $this->habit->invitations->where('status', 'pending');
    }

    #[Computed]
    public function activeParticipants(): Collection
    {
        return $this->habit->participants->where('status', 'active');
    }

    #[Computed]
    public function calendarGrid()
    {
        $startDate = Carbon::create($this->year, $this->month, 1);
        $daysInMonth = $startDate->daysInMonth;
        // dayOfWeek trả về 0 cho Chủ Nhật, 1 cho Thứ Hai, ..., 6 cho Thứ Bảy
        $startDayOfWeek = $startDate->dayOfWeek;

        $grid = [];

        // Thêm các ô trống cho những ngày trước ngày 1 của tháng
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $grid[] = null;
        }

        // Thêm các ngày trong tháng
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($this->year, $this->month, $day)->startOfDay();
            $dateString = $date->format('Y-m-d');
            $isToday = $date->isToday();
            $isPast = $date->isPast() && !$isToday;
            $isFuture = $date->isFuture();

            $log = $this->myLogs[$dateString] ?? null;

            $grid[] = [
                'day' => $day,
                'date' => $dateString,
                'is_today' => $isToday,
                'is_past' => $isPast,
                'is_future' => $isFuture,
                'status' => $log ? $log->status : 'pending'
            ];
        }
        return $grid;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.habits.habit-show');
    }
}
