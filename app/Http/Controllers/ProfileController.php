<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang hồ sơ của người dùng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $userId = $request->query('id', Auth::id());
        $user = User::findOrFail($userId);

        // Lấy các thử thách công khai, đang hoạt động mà người dùng tham gia.
        // Lưu ý: Bảng 'challenges' của bạn không có cột 'visibility'.
        // Tôi sẽ dùng cột 'type' = 'public' dựa trên migration.
        $participatedChallenges = $user->participatedChallenges()
            ->where('challenges.status', 'active')
            ->where('type', 'public')
            ->latest() // Sắp xếp theo ngày tạo, mới nhất trước
            ->get();

        // Lấy các thử thách công khai do người dùng tạo
        $createdChallenges = $user->createdChallenges()
            ->where('challenges.status', 'active')
            ->where('type', 'public')
            ->latest() // Sắp xếp theo ngày tạo, mới nhất trước
            ->get();

        // Lấy thứ hạng của người dùng dựa trên tổng XP
        $userTotalXp = $user->xpLogs()->sum('xp');

        $rankQuery = DB::table('user_xp_logs')
            ->select('user_id', DB::raw('SUM(xp) as total_xp'))
            ->groupBy('user_id')
            ->having('total_xp', '>', $userTotalXp);

        $rank = $rankQuery->count() + 1;

        return view('profile', [
            'user' => $user,
            'rank' => $rank,
            'participatedChallenges' => $participatedChallenges,
            'createdChallenges' => $createdChallenges,
        ]);
    }

    /**
     * Hiển thị trang chỉnh sửa hồ sơ cho người dùng hiện tại.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Giả sử bạn đã đổi tên file `profile.blade.php` cũ thành `profile_edit.blade.php`
        // Nếu chưa, bạn cần tạo file này với nội dung là form chỉnh sửa.
        // Dựa trên context, file `profile_edit.blade.php` đã tồn tại.
        return view('profile_edit');
    }
}