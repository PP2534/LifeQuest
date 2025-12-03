<?php

namespace App\Livewire\Habits;

use App\Models\Habit;
use App\Models\HabitParticipant;
use Livewire\Component;
use App\Models\HabitInvitation;
use App\Models\User;
use App\Services\XpService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Notifications\HabitInvitationNotification;

class HabitShow extends Component
{
    use WithFileUploads;

    public Habit $habit;
    public bool $isParticipant = false;
    public bool $isCreator = false;
    public ?string $participationStatus = null;
    public ?HabitParticipant $currentUserParticipant = null;
    public ?HabitInvitation $currentUserInvitation = null;

    public bool $showInviteModal = false;
    public bool $showDeleteModal = false;
// Lịch
    public $year;
    public $month;
    public $monthName;
// Tải bằng chứng 
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
    // Cho phép người dùng gửi yêu cầu tham gia một thói quen nhóm
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

        $this->reloadHabit();
        $this->loadParticipationData();

        session()->flash('status', 'Yêu cầu tham gia của bạn đã được gửi đi và đang chờ duyệt.');
    }
    // Cho phép người dùng hủy bỏ yêu cầu tham gia
    public function cancelRequest()
    {
        if (!$this->currentUserInvitation || $this->isCreator) {
            return;
        }

        $this->currentUserInvitation->delete();

        $this->reloadHabit();
        $this->loadParticipationData();

        session()->flash('status', 'Bạn đã hủy yêu cầu tham gia.');
    }
    // Cho phép người dùng rời khỏi thói quen 
    public function leaveHabit()
    {
        if (!$this->isParticipant || $this->isCreator) {
            return;
        }

        HabitParticipant::where('habit_id', $this->habit->id)
            ->where('user_id', Auth::id())
            ->delete();

        $this->reloadHabit();
        $this->loadParticipationData();

        session()->flash('status', 'Bạn đã rời khỏi thói quen.');
    }
    // Xóa thói quen
    public function deleteHabit()
    {
        // Chỉ người tạo mới có quyền xóa
        if (!$this->isCreator) {
            session()->flash('error', 'Bạn không có quyền xóa thói quen này.');
            return;
        }

        $this->habit->delete();

        $this->showDeleteModal = false;

        // Chuyển hướng về trang danh sách với thông báo
        session()->flash('status', 'Thói quen đã được xóa thành công.');
        return $this->redirectRoute('habits.index');
    }

    public function confirmDelete(): void
    {
        if ($this->isCreator) {
            $this->showDeleteModal = true;
        }
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
    }
    // Duyệt một yêu cầu tham gia
    public function approveRequest(int $invitationId): bool
    {
        $invitation = HabitInvitation::find($invitationId);

        // 1. Kiểm tra xem lời mời có hợp lệ không
        if (!$invitation || $invitation->habit_id !== $this->habit->id || $invitation->status !== 'pending') {
            return false;
        }

        $isJoinRequest = (int) $invitation->inviter_id === (int) $invitation->invitee_id;

        // 2. Kiểm tra quyền
        $isAllowed = false;
        $authId = Auth::id();

        if ($isJoinRequest && $this->isCreator) {
            // Người tạo duyệt yêu cầu tham gia
            $isAllowed = true;
        } elseif (!$isJoinRequest && Auth::check() && (int) $authId === (int) $invitation->invitee_id) {
            // Người được mời tự chấp nhận lời mời
            $isAllowed = true;
        }

        if (!$isAllowed) {
            session()->flash('error', 'Bạn không có quyền thực hiện hành động này.');
            return false;
        }

        DB::transaction(function () use ($invitation) {
            HabitParticipant::updateOrCreate(
                [
                    'habit_id' => $invitation->habit_id,
                    'user_id' => $invitation->invitee_id,
                ],
                [
                    'role' => 'member',
                    'status' => 'active',
                ]
            );

            $invitation->delete();
        });

        $this->reloadHabit();
        $this->loadParticipationData();
        session()->flash('status', $isJoinRequest ? 'Đã duyệt thành viên.' : 'Bạn đã tham gia thói quen!');
        return true;
    }
    // Từ chối lời mời 
    public function rejectRequest(int $invitationId): bool
    {
        $invitation = HabitInvitation::find($invitationId);

        if (!$invitation || $invitation->habit_id !== $this->habit->id || $invitation->status !== 'pending') {
            return false;
        }

        $isJoinRequest = (int) $invitation->inviter_id === (int) $invitation->invitee_id;

        // Người tạo chỉ quản lý yêu cầu tham gia; người được mời có thể từ chối lời mời
        $isAllowed = false;
        $authId = Auth::id();

        if ($isJoinRequest) {
            $isAllowed = $this->isCreator;
        } else {
            $isAllowed = Auth::check() && (int) $authId === (int) $invitation->invitee_id;
        }

        if (!$isAllowed) {
            session()->flash('error', 'Bạn không có quyền thực hiện hành động này.');
            return false;
        }

        // Delete the invitation
        $invitation->delete();

        $this->reloadHabit();
        $this->loadParticipationData();
        session()->flash('status', $isJoinRequest ? 'Đã từ chối yêu cầu.' : 'Đã từ chối lời mời.');
        return true;
    }
    public function openInviteModal(): void
    {
        if (($this->isParticipant || $this->isCreator) && $this->habit->allow_member_invite) {
            $this->showInviteModal = true;
        }
    }

    public function inviteUser(int $userId): void
    {
        if ((!$this->isParticipant && !$this->isCreator) || !$this->habit->allow_member_invite || !Auth::check()) {
            return;
        }

        if ($userId === Auth::id()) {
            session()->flash('status', 'Bạn đã tham gia thói quen này.');
            return;
        }

        $alreadyParticipant = HabitParticipant::where('habit_id', $this->habit->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyParticipant) {
            session()->flash('status', 'Người dùng này đã là thành viên.');
            return;
        }

        $pendingInvite = HabitInvitation::where('habit_id', $this->habit->id)
            ->where('invitee_id', $userId)
            ->where('status', 'pending')
            ->first();

        if ($pendingInvite) {
            session()->flash('status', 'Bạn đã gửi lời mời đến người này.');
            return;
        }

        HabitInvitation::create([
            'habit_id' => $this->habit->id,
            'inviter_id' => Auth::id(),
            'invitee_id' => $userId,
            'status' => 'pending',
        ]);

        $invitee = User::active()->find($userId);
        if ($invitee) {
            Notification::send($invitee, new HabitInvitationNotification(Auth::user(), $this->habit));
        }

        $this->reloadHabit();
        unset($this->followings);

        $this->showInviteModal = true;
        session()->flash('status', 'Đã gửi lời mời thành công.');
    }
    // Đuổi người trong nhóm
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
            $this->reloadHabit();
            $this->loadParticipationData();
            session()->flash('status', 'Đã xóa thành viên khỏi nhóm.');
        }
    }
    // Chấp nhận lời mời
    public function acceptInvitation()
    {
        if (!$this->currentUserInvitation || $this->currentUserInvitation->status !== 'pending') {
            return;
        }

        $this->approveRequest($this->currentUserInvitation->id);
    }
    // Từ chối lời mời 
    public function rejectInvitation()
    {
        if (!$this->currentUserInvitation || $this->currentUserInvitation->status !== 'pending') {
            return;
        }

        $this->rejectRequest($this->currentUserInvitation->id);
    }
    // Trạng thái Tham gia
    protected function loadParticipationData(): void
    {
        // Reset state
        $this->isParticipant = false; // người dùng tham gia
        $this->isCreator = false; // người tạo
        $this->participationStatus = null; // trạng thái tham gia
        $this->currentUserParticipant = null; 
        $this->currentUserInvitation = null; // lời mời của người dùng 

        if (!Auth::check()) return; //Kiểm tra chưa đăng nhập thì dừng

        $userId = Auth::id();

        // 1. Kiểm tra xem có phải là thành viên đang hoạt động không
        $this->currentUserParticipant = $this->habit->participants->firstWhere('user_id', $userId);

        if ($this->currentUserParticipant && $this->currentUserParticipant->status === 'active') {
            $this->isParticipant = true;
            $this->isCreator = $this->currentUserParticipant->role === 'creator';
            $this->participationStatus = 'participant';
            return;
        }

        // 2. Nếu không, kiểm tra xem có lời mời/yêu cầu đang chờ không
        $this->currentUserInvitation = $this->habit->invitations->where('invitee_id', $userId)->where('status', 'pending')->first();

        if ($this->currentUserInvitation) {
              // Nếu người mời và người được mời là một -> đây là yêu cầu tham gia
            // Ngược lại -> đây là lời mời từ người khác
            $this->participationStatus = $this->currentUserInvitation->inviter_id === $userId ? 'pending_request' : 'invited';
        }
    }

    protected function reloadHabit(): void
    {
        $habitId = $this->habit->id ?? null;

        if (!$habitId) {
            return;
        }

        $this->habit = Habit::with(['participants.user', 'invitations.invitee', 'invitations.inviter'])->findOrFail($habitId);
    }
    // Cập nhật tên tháng và năm
    public function updateMonthName()
    {
        // Đảm bảo ngôn ngữ là tiếng Việt
        Carbon::setLocale('vi');
        $this->monthName = Carbon::create($this->year, $this->month)->translatedFormat('F Y');
    }
    // Chuyển đến tháng trước
    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->updateMonthName();
        unset($this->calendarGrid); // Xóa cache để tính toán lại
    }
    // Chuyển đến tháng sau
    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->updateMonthName();
        unset($this->calendarGrid); // Xóa cache để tính toán lại
    }
    // Xử lý khi nhấn vào một ngày trên lịch
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
    //  Lưu log khi có bằng chứng (từ modal)
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

        if ($this->currentUserParticipant && $this->currentUserParticipant->user) {
            app(XpService::class)->awardDailyActivityXp($this->currentUserParticipant->user);
        }
    }
    // Đóng modal tải bằng chứng
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
    // Cập nhật streak và giao diện
    private function updateStreakForCurrentUser(): void
    {
        $this->currentUserParticipant?->calculateAndUpdateStreak(app(XpService::class));
        $this->currentUserParticipant?->refresh();
        unset($this->calendarGrid); // Force calendar to re-render with new data
    }
    // Lấy danh sách log của người dùng hiện tại
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
    public function followings(): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        $habitId = $this->habit->id;

        return Auth::user()->followingsUsers()->get()->map(function ($user) use ($habitId) {
            $isParticipant = HabitParticipant::where('habit_id', $habitId)
                ->where('user_id', $user->id)
                ->exists();

            if ($isParticipant) {
                $user->invite_status = 'active';
                return $user;
            }

            $invitation = HabitInvitation::where('habit_id', $habitId)
                ->where('invitee_id', $user->id)
                ->latest()
                ->first();

            $user->invite_status = $invitation->status ?? 'none';

            return $user;
        });
    }
    // Lấy danh sách lời mời đang chờ (chỉ cho người tạo)
    #[Computed]
    public function pendingJoinRequests(): Collection
    {
        if (!$this->isCreator) {
            return collect();
        }

        return $this->habit->invitations
            ->where('status', 'pending')
            ->filter(fn ($invitation) => (int) $invitation->inviter_id === (int) $invitation->invitee_id);
    }

    //  Lấy danh sách thành viên đang hoạt động
    #[Computed]
    public function activeParticipants(): Collection
    {
        return $this->habit->participants->where('status', 'active');
    }
    // Tạo ra cấu trúc dữ liệu cho lưới lịch
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
