<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitInvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habit_invitations = [
            [
                'habit_id' => 1,
                'inviter_id' => 1,
                'invitee_id' => 2,
                'status' => 'pending',
            ],
            [
                'habit_id' => 2,
                'inviter_id' => 2,
                'invitee_id' => 3,
                'status' => 'accepted',
            ],
            [
                'habit_id' => 3,
                'inviter_id' => 3,
                'invitee_id' => 4,
                'status' => 'rejected',
            ],
        ];
        foreach ($habit_invitations as $invitation) {
            \App\Models\HabitInvitation::create($invitation);
        }
    }
}
