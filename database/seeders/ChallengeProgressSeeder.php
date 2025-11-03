<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChallengeProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenge_progresses = [
            [
                'challenge_participant_id' => 1,
                'date' => now(),
                'status' => 'done',
                'proof_image' => 'proof.jpg',
            ],
            [
                'challenge_participant_id' => 2,
                'date' => now()->subDay(),
                'status' => 'missed',
                'proof_image' => 'proof.jpg',
            ],
            [
                'challenge_participant_id' => 1,
                'date' => now()->subDays(2),
                'status' => 'done',
                'proof_image' => 'proof.jpg',
            ],
        ];
        foreach ($challenge_progresses as $progress) {
            \App\Models\ChallengeProgress::create($progress);
        }
    }
}
