<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserXpLogs;
use App\Models\Challenge;
use Carbon\Carbon;

class XpService
{
    /**
     * Ghi lại log XP cho người dùng.
     *
     * @param User $user
     * @param int $xp
     * @param string $action
     * @param int|null $relatedId
     * @param string|null $relatedType
     * @return void
     */
    public function awardXp(User $user, int $xp, string $action, int $relatedId = null, string $relatedType = null): void
    {
        if ($xp <= 0) {
            return;
        }

        UserXpLogs::create([
            'user_id' => $user->id,
            'xp' => $xp,
            'action' => $action,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
        ]);
    }

    /**
     * Kiểm tra xem người dùng đã nhận XP cho một hành động cụ thể trong ngày chưa.
     *
     * @param User $user
     * @param string $action
     * @return bool
     */
    private function hasReceivedDailyXp(User $user, string $action): bool
    {
        return UserXpLogs::where('user_id', $user->id)
            ->where('action', $action)
            ->whereDate('created_at', Carbon::today())
            ->exists();
    }

    /**
     * Cộng điểm khi đăng nhập hàng ngày.
     */
    public function awardDailyLoginXp(User $user): void
    {
        if (!$this->hasReceivedDailyXp($user, 'daily_login')) {
            $this->awardXp($user, 2, 'daily_login');
        }
    }

    /**
     * Cộng điểm khi hoàn thành ít nhất 1 hoạt động (thói quen/thử thách) trong ngày.
     */
    public function awardDailyActivityXp(User $user): void
    {
        if (!$this->hasReceivedDailyXp($user, 'daily_activity')) {
            $this->awardXp($user, 3, 'daily_activity');
        }
    }

    /**
     * Cộng điểm khi bình luận ít nhất 1 lần trong ngày.
     */
    public function awardDailyCommentXp(User $user): void
    {
        if (!$this->hasReceivedDailyXp($user, 'daily_comment')) {
            $this->awardXp($user, 1, 'daily_comment');
        }
    }

    /**
     * Cộng điểm cho streak thói quen.
     */
    public function awardHabitStreakXp(User $user, int $streak): void
    {
        $milestones = [7 => 2, 14 => 4, 21 => 8, 28 => 16, 35 => 32];
        if (isset($milestones[$streak])) {
            $action = 'habit_streak_' . $streak;
            // Đảm bảo chỉ nhận một lần cho mỗi mốc streak
            if (!UserXpLogs::where('user_id', $user->id)->where('action', $action)->exists()) {
                $this->awardXp($user, $milestones[$streak], $action);
            }
        }
    }

    /**
     * Cộng điểm khi hoàn thành thử thách.
     */
    public function awardChallengeCompletionXp(User $user, Challenge $challenge, int $streak): void
    {
        if ($challenge->duration_days >= 3) {
            $xp = ceil(($challenge->duration_days / 3) * 1.5 + ($streak / 7) * 2);
            $this->awardXp($user, $xp, 'challenge_completion', $challenge->id, get_class($challenge));
        }
    }

    /**
     * Cộng điểm cho người tạo thử thách nổi bật.
     */
    public function awardCreatorChallengeMilestoneXp(Challenge $challenge): void
    {
        $creator = $challenge->creator;
        if (!$creator) return;

        $participantCount = $challenge->participants()->count();
        $completedCount = $challenge->participants()->where('status', 'completed')->count();

        // Mốc 1: 20 người tham gia
        if ($participantCount >= 20) {
            $action = 'creator_challenge_20_participants';
            if (!UserXpLogs::where('user_id', $creator->id)->where('action', $action)->where('related_id', $challenge->id)->exists()) {
                $this->awardXp($creator, 5, $action, $challenge->id, get_class($challenge));

                // Mốc 1.1: Thử thách > 7 ngày (chỉ cộng khi đạt mốc 1)
                if ($challenge->duration_days > 7) {
                    $actionDuration = 'creator_challenge_long_duration';
                     if (!UserXpLogs::where('user_id', $creator->id)->where('action', $actionDuration)->where('related_id', $challenge->id)->exists()) {
                        $this->awardXp($creator, 5, $actionDuration, $challenge->id, get_class($challenge));

                        // Mốc 2: >10 người hoàn thành (chỉ cộng khi đạt mốc 1.1)
                        if ($completedCount > 10) {
                            $action10 = 'creator_challenge_10_completions';
                            if (!UserXpLogs::where('user_id', $creator->id)->where('action', $action10)->where('related_id', $challenge->id)->exists()) {
                                $this->awardXp($creator, 10, $action10, $challenge->id, get_class($challenge));
                            }
                        }

                        // Mốc 3: >20 người hoàn thành (chỉ cộng khi đạt mốc 1.1)
                        if ($completedCount > 20) {
                            $action20 = 'creator_challenge_20_completions';
                            if (!UserXpLogs::where('user_id', $creator->id)->where('action', $action20)->where('related_id', $challenge->id)->exists()) {
                                $this->awardXp($creator, 20, $action20, $challenge->id, get_class($challenge));
                            }
                        }
                    }
                }
            }
        }
    }
}