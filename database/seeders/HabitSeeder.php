<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habits = [
            [
                'title' => 'Đọc sách ít nhất 30 phút mỗi ngày',
                'description' => '<p>Phát triển thói quen đọc sách hàng ngày để nâng cao kiến thức và kỹ năng.</p>',
                'image' => 'habit.png',
                'creator_id' => 1,
                'allow_request_join' => true,
                'allow_member_invite' => true
            ],
            [
                'title' => 'Tập thể dục ít nhất 20 phút mỗi ngày',
                'description' => '<p>Duy trì thói quen tập thể dục hàng ngày để cải thiện sức khỏe và thể lực.</p>',
                'image' => 'habit.png',
                'creator_id' => 2,
                'allow_request_join' => true,
                'allow_member_invite' => true
            ],
            [
                'title' => 'Uống đủ 2 lít nước mỗi ngày',
                'description' => '</p>Hình thành thói quen uống đủ nước hàng ngày để duy trì sức khỏe tốt.</p>',
                'image' => 'habit.png',
                'creator_id' => 3,
                'allow_request_join' => true,
                'allow_member_invite' => true
            ],

        ];

        foreach ($habits as $habit) {
            \App\Models\Habit::create($habit);
        }
    }
}
