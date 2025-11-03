<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChallengeInvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenge_invitations = [
            [
                'challenge_id' => 1,
                'inviter_id' => 1,
                'invitee_id' => 2,
                'status' => 'pending',
            ],
            [
                'challenge_id' => 2,
                'inviter_id' => 2,
                'invitee_id' => 3,
                'status' => 'accepted',
            ],
            [
                'challenge_id' => 3,
                'inviter_id' => 3,
                'invitee_id' => 4,
                'status' => 'rejected',
            ],
        ];
        foreach ($challenge_invitations as $invitation) {
            \App\Models\ChallengeInvitation::create($invitation);
        }
    }
}
