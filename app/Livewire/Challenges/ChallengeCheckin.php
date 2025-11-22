<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\ChallengeProgress;
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

    public function mount(Challenge $challenge)
    {
        $this->challenge = $challenge;
        $this->participant = $challenge->participants()
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        
        $this->generateCalendar();
    }

  
    protected function rules()
    {
        $rules = [
            'selectedDate' => 'required|date',
        ];

        // Chỉ yêu cầu ảnh nếu challenge yêu cầu VÀ người dùng đang upload ảnh mới
        // (Lưu ý: logic này có thể tùy chỉnh. Ở đây ta bắt buộc nếu need_proof = true)
        if ($this->challenge->need_proof) {
            $rules['proofImage'] = 'required|image|max:5120'; // 5MB
        } else {
            $rules['proofImage'] = 'nullable|image|max:5120';
        }

        return $rules;
    }

    public function generateCalendar()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeek; 

        $logs = $this->participant->progressLogs()
            ->whereYear('date', $this->currentYear)
            ->whereMonth('date', $this->currentMonth)
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->date)->day;
            });

        $calendar = [];
        
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendar[] = null;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $status = isset($logs[$day]) ? $logs[$day]->status : null;
            $currentDate = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
            
            $calendar[] = [
                'day' => $day,
                'status' => $status,
                'is_future' => $currentDate->isFuture(),
                'is_today' => $currentDate->isToday(),
                'date_string' => $currentDate->format('Y-m-d')
            ];
        }

        $this->daysInMonth = $calendar;
    }

    public function selectDate($day)
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
        if ($date->isFuture()) return;

        $this->selectedDate = $date->format('Y-m-d');
        $this->selectedDateDisplay = $date->format('d/m/Y');
        
        $existingLog = $this->participant->progressLogs()->whereDate('date', $date)->first();
        $this->currentStatusOnDate = $existingLog ? $existingLog->status : null;
        
        $this->proofImage = null;
        $this->showModal = true;
        
        // Xóa các lỗi validation cũ khi mở modal mới
        $this->resetValidation();
    }

    public function markAsDone()
    {
        // Gọi validate() không tham số -> nó sẽ tự tìm hàm rules() ở trên
        $this->validate();

        $imagePath = null;
        if ($this->proofImage) {
            $imagePath = $this->proofImage->store('proofs', 'public');
        }

        // Tìm record cũ để lấy ảnh cũ nếu không up ảnh mới (cho trường hợp sửa)
        $existingLog = ChallengeProgress::where('challenge_participant_id', $this->participant->id)
            ->where('date', $this->selectedDate)
            ->first();

        $finalImagePath = $imagePath ?? ($existingLog ? $existingLog->proof_image : null);

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

        $this->finishAction('Đã đánh dấu hoàn thành!');
    }

    public function markAsMissed()
    {
        // Không cần validate ảnh khi đánh dấu Missed
        
        ChallengeProgress::updateOrCreate(
            [
                'challenge_participant_id' => $this->participant->id,
                'date' => $this->selectedDate,
            ],
            [
                'status' => 'missed',
                // Giữ nguyên ảnh cũ hoặc set null tùy logic của bạn. 
                // Ở đây ta giữ nguyên để tránh mất dữ liệu vô tình.
            ]
        );

        $this->finishAction('Đã ghi nhận bỏ lỡ ngày này.');
    }

    private function finishAction($message)
    {
        $this->participant->recalculateStats();
        $this->showModal = false;
        $this->proofImage = null; // Reset ảnh upload
        $this->generateCalendar();
        session()->flash('success', $message);
    }

    public function previousMonth() {
        $this->currentMonth--;
        if ($this->currentMonth < 1) { $this->currentMonth = 12; $this->currentYear--; }
        $this->generateCalendar();
    }

    public function nextMonth() {
        $this->currentMonth++;
        if ($this->currentMonth > 12) { $this->currentMonth = 1; $this->currentYear++; }
        $this->generateCalendar();
    }

    public function render()
    {
        return view('livewire.challenges.challenge-checkin')->layout('layouts.app');
    }
}