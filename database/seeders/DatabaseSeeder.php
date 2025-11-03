<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ChallengeSeeder::class);
        $this->call(ChallengeCategorieSeeder::class);
        $this->call(ChallengeParticipantSeeder::class);
        $this->call(ChallengeProgressSeeder::class);
        $this->call(HabitSeeder::class);
        $this->call(HabitParticipantSeeder::class);
        $this->call(HabitLogSeeder::class);
        $this->call(FollowerSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(HabitInvitationSeeder::class);
        $this->call(ChallengeInvitationSeeder::class);
    }
}
