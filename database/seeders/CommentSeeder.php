<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [
            [
                'user_id' => 1,
                'challenge_id' => 1,
                'content' => '<p>Thách thức rất hữu ích, giúp tôi duy trì thói quen uống nước đều đặn mỗi ngày!</p>',
            ],
            [
                'user_id' => 2,
                'challenge_id' => 2,
                'content' => '<p>Tôi rất thích thách thức này, nó giúp tôi phát triển thói quen đọc sách hàng ngày.</p>',
            ],
            [
                'user_id' => 3,
                'challenge_id' => 3,
                'content' => '<p>Thách thức tập thể dục hàng ngày thật tuyệt vời, tôi cảm thấy khỏe mạnh hơn rất nhiều!</p>',
            ],
        ];
        
        foreach ($comments as $comment) {
            \App\Models\Comment::create($comment);
        }
    }
}
