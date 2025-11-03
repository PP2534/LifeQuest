<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'name' => "User $i",
                'email' => "user$i@lifequest.vn",
                'password' => Hash::make('12345678'),
                'avatar' => 'default.png',
                'bio' => "This is bio for user $i",
                'interests' => 'coding, reading, cycling',
                'ward_id' => '3356',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
