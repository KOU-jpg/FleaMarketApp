<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class ItemImagetableseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


// ItemImageTableSeeder.php
public function run()
{
    // ファイル名と商品名のマッピング
    $fileItemMapping = [
        'Armani+Mens+Clock.jpg' => '腕時計',
        'HDD+Hard+Disk.jpg' => 'HDD',
        'iLoveIMG+d.jpg' => '玉ねぎ3束',
        'Leather+Shoes+Product+Photo.jpg' => '革靴',
        'Living+Room+Laptop.jpg' => 'ノートPC',
        'Music+Mic+4632231.jpg' => 'マイク',
        'Purse+fashion+pocket.jpg' => 'ショルダーバッグ',
        'Tumbler+souvenir.jpg' => 'タンブラー',
        'Waitress+with+Coffee+Grinder.jpg' => 'コーヒーミル',
        '外出メイクアップセット.jpg' => 'メイクセット',
    ];
    
    $items = DB::table('items')->get()->keyBy('name');
    $images = [];
    
    foreach ($fileItemMapping as $fileName => $itemName) {
        if (isset($items[$itemName])) {
            $images[] = [
                'item_id' => $items[$itemName]->id,
                'path' => 'Images/Item_images_sample/' . $fileName,
                'order' => 1, // メイン画像として設定
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
    }
    
    // バルクインサート
    DB::table('item_images')->insert($images);
}
}
