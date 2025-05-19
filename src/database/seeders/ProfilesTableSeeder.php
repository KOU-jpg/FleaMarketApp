<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->truncate();
        
        // ユーザーIDごとにプロフィールを登録
        $profiles = [
            [
                'user_id' => 1,
                'postal_code' => '100-0001',
                'address' => '東京都千代田区千代田1-1',
                'building' => 'テストビル101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'postal_code' => '150-0001',
                'address' => '東京都渋谷区渋谷2-2-2',
                'building' => 'サンプルマンション202',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'postal_code' => '160-0001',
                'address' => '東京都新宿区新宿3-3-3',
                'building' => 'デモタワー303',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('profiles')->insert($profiles);
    }
}
