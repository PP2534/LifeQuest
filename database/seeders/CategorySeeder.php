<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sức khỏe và thể hình',
                'slug' => 'suc-khoe-va-the-hinh',
                'description' => 'Thách thức liên quan đến việc cải thiện sức khỏe và thể hình.',
                'icon' => 'fitness-icon.png',
            ],
            [
                'name' => 'Phát triển cá nhân',
                'slug' => 'phat-trien-ca-nhan',
                'description' => 'Thách thức giúp phát triển kỹ năng và kiến thức cá nhân.',
                'icon' => 'personal-development-icon.png',
            ],
            [
                'name' => 'Sự nghiệp và công việc',
                'slug' => 'su-nghiep-va-cong-viec',
                'description' => 'Thách thức liên quan đến sự nghiệp và công việc.',
                'icon' => 'career-icon.png',
            ],
            [
                'name' => 'Tài chính cá nhân',
                'slug' => 'tai-chinh-ca-nhan',
                'description' => 'Thách thức giúp quản lý và cải thiện tài chính cá nhân.',
                'icon' => 'finance-icon.png',
            ],
            [
                'name' => 'Mối quan hệ và xã hội',
                'slug' => 'moi-quan-he-va-xa-hoi',
                'description' => 'Thách thức liên quan đến việc xây dựng và duy trì mối quan hệ xã hội.',
                'icon' => 'social-icon.png',
            ],
        ];
        
        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
