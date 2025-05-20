<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    private array $users = [
        1 => [
            'postal_code' => '100-0001',
            'address'     => '東京都千代田区千代田1-1',
            'building'    => 'テストビル101',
            'image'       => '6eHVNgLDoFjEAKBWnGWDFmEIToMtazOLyN8sIYIC.jpg'
        ],
        2 => [
            'postal_code' => '150-0001',
            'address'     => '東京都渋谷区渋谷2-2-2',
            'building'    => 'サンプルマンション202',
            'image'       => '42n1nRGehnch81bKNxM7XdyrmqtKbU30VAEZ8VM2.jpg'
        ],
        3 => [
            'postal_code' => '160-0001',
            'address'     => '東京都新宿区新宿3-3-3',
            'building'    => 'デモタワー303',
            'image'       => 'boqjGYgHaCjqWZZWOFslRCIQSBuQkhuLhwQtxE6W.png'
        ]
    ];

    public function run()
    {
        DB::table('profiles')->truncate();

        $profiles = [];
        foreach ($this->users as $userId => $data) {
            $profiles[] = [
                'user_id'      => $userId,
                'postal_code'  => $data['postal_code'],
                'address'      => $data['address'],
                'building'     => $data['building'],
                'image_path'   => 'Images/profile_images_sample/' . $data['image'],
                'created_at'   => now(),
                'updated_at'   => now()
            ];
        }

        DB::table('profiles')->insert($profiles);
    }
}