<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habit_participants = [
            [
                'habit_id' => 1,
                'user_id' => 1,
                'role' => 'creator',
                'status' => 'active',
            ],
            [
                'habit_id' => 1,
                'user_id' => 2,
                'role' => 'member',
                'status' => 'active',
            ],
            [
                'habit_id' => 2,
                'user_id' => 3,
                'role' => 'creator',
                'status' => 'active',
            ],
        ];

        foreach ($habit_participants as $participant) {
            \App\Models\HabitParticipant::create($participant);
        }
    }
}
