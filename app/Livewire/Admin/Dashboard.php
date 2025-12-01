<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\User;
use App\Models\Challenge;
use App\Models\Habit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Import Carbon để xử lý ngày tháng

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public $totalUsers;
    public $totalAdmins;
    public $totalChallenges;
    public $totalHabits;
    public $unverifiedUsersCount;
    public $latestChallenges;
    public $userGrowthData = ['labels' => [], 'data' => []]; // Thêm thuộc tính này

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->totalUsers = User::count();
        $this->totalAdmins = User::where('role', 'admin')->count();
        $this->totalChallenges = Challenge::count();
        $this->totalHabits = Habit::count();
        $this->unverifiedUsersCount = User::whereNull('email_verified_at')->count();
        
        $this->latestChallenges = Challenge::latest()->take(5)->get();

        // Lấy dữ liệu tăng trưởng người dùng cho biểu đồ
        $this->loadUserGrowthData();
    }

    private function loadUserGrowthData()
    {
        $days = 30; // Lấy dữ liệu 30 ngày gần nhất
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $usersByDay = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labels = [];
        $data = [];
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d'); // Format ngày cho biểu đồ (e.g., Oct 01)
            $count = $usersByDay->where('date', $dateString)->first();
            $data[] = $count ? $count->count : 0;
            $currentDate->addDay();
        }

        $this->userGrowthData = [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}