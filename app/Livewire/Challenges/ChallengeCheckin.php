<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\ChallengeProgress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ChallengeCheckin extends Component
{
    use WithFileUploads;

    public Challenge $challenge;
    public ?ChallengeParticipant $participant = null;

    // Dữ liệu cho Lịch
    public $currentMonth;
    public $currentYear;
    public $daysInMonth = [];
    
    // Dữ liệu Form Điểm danh
    public $selectedDate = null;
    public $proofImage;
    public $showModal = false;

    public function mount(Challenge $challenge)
    {
        $this->challenge = $challenge;
        $this->participant = $challenge->participants()
            ->where('user_id', Auth::id())
            ->firstOrFail(); // Bắt buộc phải là người tham gia mới vào được

        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        
        $this->generateCalendar();
    }

    public function generateCalendar()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeek; // 0 (Sun) - 6 (Sat)

        // Lấy dữ liệu đã điểm danh trong tháng này
        $logs = $this->participant->progressLogs()
            ->whereYear('date', $this->currentYear)
            ->whereMonth('date', $this->currentMonth)
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->date)->day;
            });

        $calendar = [];
        
        // Ô trống đầu tháng
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendar[] = null;
        }

        // Các ngày trong tháng
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $status = isset($logs[$day]) ? $logs[$day]->status : null;
            
            // Kiểm tra xem ngày này có phải tương lai không
            $currentDate = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
            $isFuture = $currentDate->isFuture();
            $isToday = $currentDate->isToday();

            $calendar[] = [
                'day' => $day,
                'status' => $status,
                'is_future' => $isFuture,
                'is_today' => $isToday,
                'date_string' => $currentDate->format('Y-m-d')
            ];
        }

        $this->daysInMonth = $calendar;
    }

    public function selectDate($day)
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
        
        // Không cho điểm danh ngày tương lai
        if ($date->isFuture()) return;

        // Nếu đã điểm danh rồi thì thôi (hoặc cho sửa tùy logic, ở đây ta chặn)
        $exists = $this->participant->progressLogs()->whereDate('date', $date)->exists();
        if ($exists) return;

        $this->selectedDate = $date->format('Y-m-d');
        $this->showModal = true;
    }

    public function submitCheckin()
    {
        // Validate
        $rules = [
            'selectedDate' => 'required|date',
        ];

        if ($this->challenge->need_proof) {
            $rules['proofImage'] = 'required|image|max:5120'; // 5MB
        }

        $this->validate($rules);

        $imagePath = null;
        if ($this->proofImage) {
            $imagePath = $this->proofImage->store('proofs', 'public');
        }

        // Lưu vào DB
        ChallengeProgress::create([
            'challenge_participant_id' => $this->participant->id,
            'date' => $this->selectedDate,
            'status' => 'done',
            'proof_image' => $imagePath
        ]);

        // === TÍNH TOÁN LẠI TIẾN ĐỘ & STREAK ===
        $this->participant->recalculateStats();

        // Reset & Đóng modal
        $this->showModal = false;
        $this->proofImage = null;
        
        // Tạo lại lịch để cập nhật màu xanh
        $this->generateCalendar();
        
        session()->flash('success', 'Đã điểm danh thành công!');
    }

    public function previousMonth() {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendar();
    }

    public function nextMonth() {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendar();
    }

    public function render()
    {
        return view('livewire.challenges.challenge-checkin')->layout('layouts.app');
    }
}