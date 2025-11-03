<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChallengeParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenge_participants = [
            [
                'challenge_id' => 1,
                'user_id' => 1,
                'progress_percent' => 50,
                'streak' => 5,
                'role' => 'creator',
                'personal_start_date' => now()->subDays(10),
                'personal_end_date' => now()->addDays(20),
            ],
            [
                'challenge_id' => 1,
                'user_id' => 2,
                'progress_percent' => 30,
                'streak' => 3,
                'role' => 'member',
                'personal_start_date' => now()->subDays(5),
                'personal_end_date' => now()->addDays(25),
            ],
            [
                'challenge_id' => 2,
                'user_id' => 2,
                'progress_percent' => 70,
                'streak' => 7,
                'role' => 'creator',
                'personal_start_date' => now()->subDays(15),
                'personal_end_date' => now()->addDays(15),
            ],
        ];
        foreach ($challenge_participants as $participant) {
            \App\Models\ChallengeParticipant::create($participant);
        }
    }
}
