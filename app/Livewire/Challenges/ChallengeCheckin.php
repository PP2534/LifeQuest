<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\ChallengeProgress;
use App\Services\XpService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

class ChallengeCheckin extends Component
{
    use WithFileUploads;

    public Challenge $challenge;
    public ?ChallengeParticipant $participant = null;

    // Dữ liệu Lịch
    public $currentMonth;
    public $currentYear;
    public $daysInMonth = [];
    
    // Dữ liệu Modal & Form
    public $selectedDate = null;
    public $selectedDateDisplay = null;
    public $proofImage;
    public $showModal = false;
    public $currentStatusOnDate = null; 

    /**
     * Hàm khởi tạo component (Chạy 1 lần khi trang được tải)
     * Nhiệm vụ: Tải thông tin thử thách, người tham gia và khởi tạo lịch tháng hiện tại.
     */
    public function mount(Challenge $challenge)
    {
        if ($challenge->status !== 'active') {
            abort(404);
        }
        $this->challenge = $challenge;
        
        // Lấy thông tin tham gia của user hiện tại
        $this->participant = $challenge->participants()
            ->where('user_id', Auth::id())
            ->with('user')
            ->firstOrFail();

        // Mặc định hiển thị tháng và năm hiện tại
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        
        // Tạo dữ liệu cho lịch
        $this->generateCalendar();
    }

    /**
     * Định nghĩa các luật validation (kiểm tra dữ liệu đầu vào)
     */
    protected function rules()
    {
        $rules = [
            'selectedDate' => 'required|date',
        ];

        // Kiểm tra logic: Nếu challenge yêu cầu ảnh -> bắt buộc phải up ảnh (trừ khi đã có ảnh cũ, logic xử lý ở hàm submit)
        if ($this->challenge->need_proof) {
            $rules['proofImage'] = 'required|image|max:5120'; // Tối đa 5MB
        } else {
            $rules['proofImage'] = 'nullable|image|max:5120';
        }

        return $rules;
    }

    /**
     * Hàm tạo dữ liệu cho lưới lịch (Calendar Grid)
     * Nhiệm vụ: Tính toán các ngày trong tháng, trạng thái (done/missed) và các cờ (is_future, is_before_start).
     */
    public function generateCalendar()
    {
        // Tạo ngày đầu tháng
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeek; // 0 (Chủ nhật) -> 6 (Thứ 7)

        // Lấy ngày bắt đầu tham gia của người dùng (để chặn click những ngày trước đó)
        $startDate = Carbon::parse($this->participant->personal_start_date)->startOfDay();

        // Lấy lịch sử điểm danh trong tháng này để hiển thị màu sắc
        $logs = $this->participant->progressLogs()
            ->whereYear('date', $this->currentYear)
            ->whereMonth('date', $this->currentMonth)
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->date)->day;
            });

        $calendar = [];
        
        // Thêm các ô trống cho những ngày thuộc tháng trước ở đầu tuần
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendar[] = null;
        }

        // Vòng lặp tạo từng ngày trong tháng
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $status = isset($logs[$day]) ? $logs[$day]->status : null;
            $currentDate = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day)->startOfDay();
            
            $calendar[] = [
                'day' => $day,
                'status' => $status,
                'is_future' => $currentDate->isFuture(), // Kiểm tra ngày tương lai
                'is_today' => $currentDate->isToday(),   // Kiểm tra hôm nay
                'is_before_start' => $currentDate->lt($startDate), // Kiểm tra ngày trước khi tham gia
                'date_string' => $currentDate->format('Y-m-d')
            ];
        }

        $this->daysInMonth = $calendar;
    }

    /**
     * Hàm xử lý khi người dùng click vào một ngày trên lịch
     * Nhiệm vụ: Kiểm tra hợp lệ (không phải tương lai, không phải trước ngày tham gia) rồi mở Modal.
     */
    public function selectDate($day)
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day)->startOfDay();
        
        //  Chặn chọn ngày tương lai
        if ($date->isFuture()) return;

        //  Chặn chọn ngày trước khi bắt đầu tham gia
        $startDate = Carbon::parse($this->participant->personal_start_date)->startOfDay();
        if ($date->lt($startDate)) return;

        $this->selectedDate = $date->format('Y-m-d');
        $this->selectedDateDisplay = $date->format('d/m/Y');
        
        // Kiểm tra xem ngày này đã có dữ liệu cũ chưa
        $existingLog = $this->participant->progressLogs()->whereDate('date', $date)->first();
        $this->currentStatusOnDate = $existingLog ? $existingLog->status : null;
        
        // Reset form trong modal
        $this->proofImage = null;
        $this->showModal = true;
        
        // Xóa các lỗi validation cũ
        $this->resetValidation();
    }

    /**
     * Hàm xử lý nút "Xác nhận Hoàn thành" (submitCheckin)
     
     */
    public function markAsDone(XpService $xpService)
    {
        // Gọi validate() để kiểm tra dữ liệu theo rules()
        $this->validate();

        $imagePath = null;
        if ($this->proofImage) {
            $imagePath = $this->proofImage->store('proofs', 'public');
        }

        // Tìm log cũ (nếu có) để giữ lại ảnh cũ nếu người dùng không up ảnh mới khi sửa
        $existingLog = ChallengeProgress::where('challenge_participant_id', $this->participant->id)
            ->where('date', $this->selectedDate)
            ->first();

        $finalImagePath = $imagePath ?? ($existingLog ? $existingLog->proof_image : null);

        // Lưu hoặc cập nhật vào bảng challenge_progress
        ChallengeProgress::updateOrCreate(
            [
                'challenge_participant_id' => $this->participant->id,
                'date' => $this->selectedDate,
            ],
            [
                'status' => 'done',
                'proof_image' => $finalImagePath
            ]
        );

        $this->finishAction('Đã đánh dấu hoàn thành!', $xpService);
    }

    /**
     * Hàm xử lý nút "Xác nhận Bỏ lỡ"
     */
    public function markAsMissed()
    {
        // Lưu trạng thái 'missed'
        ChallengeProgress::updateOrCreate(
            [
                'challenge_participant_id' => $this->participant->id,
                'date' => $this->selectedDate,
            ],
            [
                'status' => 'missed',
                // Giữ nguyên ảnh cũ (nếu có) để tránh mất dữ liệu vô tình
            ]
        );

        $this->finishAction('Đã ghi nhận bỏ lỡ ngày này.');
    }

    /**
     * Hàm phụ trợ: Xử lý các công việc chung sau khi Lưu/Cập nhật
     */
    private function finishAction($message, ?XpService $xpService = null)
    {
        $this->participant->recalculateStats(); // Tính lại % và Streak
        $this->participant->refresh();

        if ($xpService) {
            $participantUser = $this->participant->user ?? $this->participant->loadMissing('user')->user;
            if ($participantUser) {
                $xpService->awardDailyActivityXp($participantUser);

                if ($this->participant->progress_percent >= 100) {
                    $xpService->awardChallengeCompletionXp(
                        $participantUser,
                        $this->challenge,
                        $this->participant->streak
                    );

                    $xpService->awardCreatorChallengeMilestoneXp($this->challenge);
                }
            }
        }

        $this->showModal = false;               // Đóng modal
        $this->proofImage = null;               // Reset ảnh
        $this->generateCalendar();              // Vẽ lại lịch để cập nhật màu sắc mới
        session()->flash('success', $message);  // Hiển thị thông báo
    }

    /**
     * Chuyển sang tháng trước
     */
    public function previousMonth() {
        $this->currentMonth--;
        if ($this->currentMonth < 1) { 
            $this->currentMonth = 12; 
            $this->currentYear--; 
        }
        $this->generateCalendar();
    }

    /**
     * Chuyển sang tháng sau
     */
    public function nextMonth() {
        $this->currentMonth++;
        if ($this->currentMonth > 12) { 
            $this->currentMonth = 1; 
            $this->currentYear++; 
        }
        $this->generateCalendar();
    }

    public function render()
    {
        return view('livewire.challenges.challenge-checkin')->layout('layouts.app');
    }
}