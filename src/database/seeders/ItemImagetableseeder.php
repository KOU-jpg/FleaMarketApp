<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemImagetableseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ファイル名と商品名のマッピング
        $fileItemMapping = [
            'Armani+Mens+Clock.jpg'        => '腕時計',
            'HDD+Hard+Disk.jpg'            => 'HDD',
            'iLoveIMG+d.jpg'               => '玉ねぎ3束',
            'Leather+Shoes+Product+Photo.jpg' => '革靴',
            'Living+Room+Laptop.jpg'       => 'ノートPC',
            'Music+Mic+4632231.jpg'        => 'マイク',
            'Purse+fashion+pocket.jpg'     => 'ショルダーバッグ',
            'Tumbler+souvenir.jpg'         => 'タンブラー',
            'Waitress+with+Coffee+Grinder.jpg' => 'コーヒーミル',
            '外出メイクアップセット.jpg'      => 'メイクセット',
        ];

        // itemsテーブルから商品名でIDを引く
        $items = DB::table('items')->get()->keyBy('name');
        $images = [];

        foreach ($fileItemMapping as $fileName => $itemName) {
            if (isset($items[$itemName])) {
                $images[] = [
                    'item_id'    => $items[$itemName]->id,
                    'path'       => 'images/Item_image_samples/' . $fileName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // バルクインサート
        DB::table('item_images')->insert($images);
    }
}
