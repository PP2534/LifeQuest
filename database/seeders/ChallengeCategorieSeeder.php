<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChallengeCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenge_categories = [
            [
                'challenge_id' => 1,
                'category_id' => 1,
            ],
            [
                'challenge_id' => 1,
                'category_id' => 2,
            ],
            [
                'challenge_id' => 2,
                'category_id' => 3,
            ],
        ];
        foreach ($challenge_categories as $cc) {
            \App\Models\ChallengeCategorie::create($cc);
        }
    }
}
