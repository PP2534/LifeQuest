<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habit_logs = [
            [
                'habit_participant_id' => 1,
                'date' => '2023-10-01',
                'status' => 'done',
                'proof_image' => 'proof.jpg',
            ],
            [
                'habit_participant_id' => 1,
                'date' => '2023-10-02',
                'status' => 'missed',
                'proof_image' => 'proof.jpg',
            ],
            [
                'habit_participant_id' => 2,
                'date' => '2023-10-01',
                'status' => 'done',
                'proof_image' => 'proof.jpg',
            ],
        ];

        foreach ($habit_logs as $log) {
            \App\Models\HabitLog::create($log);
        }
    }
}
