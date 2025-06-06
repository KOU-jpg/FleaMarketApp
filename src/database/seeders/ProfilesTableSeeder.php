<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('profiles')->truncate();

        $profiles = [
            [
                'user_id' => 1,
                'postal_code' => '100-0001',
                'address' => '東京都千代田区千代田1-1',
                'building' => 'テストビル101',
                'image_path' => 'images/profile_image_samples/にこにこ黄色.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'postal_code' => '150-0001',
                'address' => '東京都渋谷区渋谷2-2-2',
                'building' => 'サンプルマンション202',
                'image_path' => 'images/profile_image_samples/にこにこ桜.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'postal_code' => '160-0001',
                'address' => '東京都新宿区新宿3-3-3',
                'building' => 'デモタワー303',
                'image_path' => 'images/profile_image_samples/にこにこ紫.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('profiles')->insert($profiles);
    }
}
