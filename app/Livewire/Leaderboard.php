<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Leaderboard extends Component
{
    public string $period = 'all'; // all, week, month, year

    public function render()
    {
        $query = User::select('users.id', 'users.name', 'users.avatar', DB::raw('SUM(user_xp_logs.xp) as total_xp'))
            ->leftJoin('user_xp_logs', 'users.id', '=', 'user_xp_logs.user_id')
            ->where('user_xp_logs.xp', '>', 0)
            ->where('users.role', '!=', 'admin');

        // Lọc theo khoảng thời gian
        $this->applyPeriodFilter($query);

        $topUsers = $query->groupBy('users.id', 'users.name', 'users.avatar')
            ->orderByDesc('total_xp')
            ->orderBy('users.name', 'asc')
            ->take(20)
            ->get();

        $currentUserData = null;
        if (auth()->check()) {
            $currentUserId = auth()->id();
            // Kiểm tra xem người dùng hiện tại có trong top 20 không
            $currentUserInTop = $topUsers->firstWhere('id', $currentUserId);

            if (!$currentUserInTop) {
                // Nếu không, truy vấn riêng thông tin và thứ hạng của họ
                $userXpQuery = DB::table('user_xp_logs')
                    ->join('users', 'users.id', '=', 'user_xp_logs.user_id')
                    ->where('users.role', '!=', 'admin')
                    ->select('user_xp_logs.user_id', DB::raw('SUM(user_xp_logs.xp) as total_xp'))
                    ->groupBy('user_xp_logs.user_id');

                $this->applyPeriodFilter($userXpQuery, 'user_xp_logs.created_at');

                $subQuery = $userXpQuery->toSql();
                $bindings = $userXpQuery->getBindings();

                // Sử dụng window function để tính rank
                $rankQuery = DB::table(DB::raw("({$subQuery}) as user_xps"))
                    ->mergeBindings($userXpQuery)
                    ->select('user_id', 'total_xp', DB::raw('RANK() OVER (ORDER BY total_xp DESC) as `rank`'));

                $userRankData = DB::table(DB::raw("({$rankQuery->toSql()}) as ranks"))
                    ->mergeBindings($rankQuery)
                    ->where('user_id', $currentUserId)
                    ->first();

                if ($userRankData) {
                    $user = User::find($currentUserId);
                    $currentUserData = (object) [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar,
                        'total_xp' => $userRankData->total_xp,
                        'rank' => $userRankData->rank,
                    ];
                }
            }
        }

        return view('livewire.leaderboard', [
            'users' => $topUsers,
            'currentUserData' => $currentUserData,
        ]);
    }

    private function applyPeriodFilter($query, $dateColumn = 'user_xp_logs.created_at')
    {
        switch ($this->period) {
            case 'week':
                $query->whereBetween($dateColumn, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween($dateColumn, [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                break;
            case 'year':
                $query->whereBetween($dateColumn, [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                break;
        }
    }

    public function setPeriod(string $period)
    {
        $this->period = $period;
    }
}
