<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenges = [
            [
                'title' => '7 ngày uống đủ 2 lít nước mỗi ngày',
                'description' => '<p>Thách thức giúp bạn hình thành thói quen uống đủ nước hàng ngày.</p>',
                'image' => 'challenge.png',
                'creator_id' => 1,
                'time_mode' => 'rolling',
                'streak_mode' => 'cumulative',
                'duration_days' => 7,
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'type' => 'public',
                'allow_request_join' => true,
                'allow_member_invite' => true,
                'ward_id' => '3356',
            ],
            [
                'title' => '30 ngày đọc sách mỗi ngày 20 trang',
                'description' => '<p>Thách thức giúp bạn phát triển thói quen đọc sách hàng ngày.</p>',
                'image' => 'challenge.png',
                'creator_id' => 2,
                'time_mode' => 'fixed',
                'streak_mode' => 'continuous',
                'duration_days' => 30,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'type' => 'public',
                'allow_request_join' => true,
                'allow_member_invite' => true,
                'ward_id' => '3356',
            ],
            [
                'title' => '14 ngày tập thể dục mỗi ngày 30 phút',
                'description' => '<p>Thách thức giúp bạn duy trì thói quen tập thể dục hàng ngày.</p>',
                'image' => 'challenge.png',
                'creator_id' => 3,
                'time_mode' => 'rolling',
                'streak_mode' => 'cumulative',
                'duration_days' => 14,
                'start_date' => now(),
                'end_date' => now()->addDays(14),
                'type' => 'public',
                'allow_request_join' => true,
                'allow_member_invite' => true,
                'ward_id' => '3356',
            ],
        ];
        
        foreach ($challenges as $challenge) {
            \App\Models\Challenge::create($challenge);
        }
    }
}