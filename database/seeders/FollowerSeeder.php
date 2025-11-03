<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $followers = [
            [
                'follower_id' => 2,
                'following_id' => 1,
            ],
            [
                'follower_id' => 3,
                'following_id' => 1,
            ],
            [
                'follower_id' => 1,
                'following_id' => 2,
            ],
        ];
        
        foreach ($followers as $follower) {
            \App\Models\Follower::create($follower);
        }
    }
}
