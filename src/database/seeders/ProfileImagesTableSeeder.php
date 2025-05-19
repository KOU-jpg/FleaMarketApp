<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 実際の画像ファイル名
        $imageFiles = [
            '6eHVNgLDoFjEAKBWnGWDFmEIToMtazOLyN8sIYIC.jpg',
            '42n1nRGehnch81bKNxM7XdyrmqtKbU30VAEZ8VM2.jpg',
            'boqjGYgHaCjqWZZWOFslRCIQSBuQkhuLhwQtxE6W.png',
        ];

        // ユーザーIDごとに画像を割り当てて登録
        foreach ([1, 2, 3] as $index => $userId) {
            DB::table('profile_images')->insert([
                'user_id' => $userId,
                // storage:linkしている前提。DBにはpublicを除いたパスを保存
                'path' => 'Images/profile_images_sample/' . $imageFiles[$index],
                'created_at' => now(),
            ]);
        }
    }
}
