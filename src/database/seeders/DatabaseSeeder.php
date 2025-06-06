<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
            // profile_imagesディレクトリ内のファイルを全削除
    if (Storage::disk('public')->exists('images/profile_images')) {
        $files = Storage::disk('public')->files('images/profile_images');
        Storage::disk('public')->delete($files);
    }

    // Item_imagesディレクトリ内のファイルを全削除
    if (Storage::disk('public')->exists('images/Item_images')) {
        $files = Storage::disk('public')->files('images/Item_images');
        Storage::disk('public')->delete($files);
    }



    
        // 外部キー制約を一時的に無効化
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 必要なテーブルをtruncate
        \DB::table('item_images')->truncate();
        \DB::table('comments')->truncate();
        \DB::table('category_items')->truncate();
        \DB::table('items')->truncate();
        \DB::table('profiles')->truncate();
        \DB::table('users')->truncate();
        // 他の関連テーブルもここでtruncate

        // 外部キー制約を有効化
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // その後、各Seederを呼び出す
        $this->call([
            UserSeeder::class,
            ProfilesTableSeeder::class,
            ConditionsTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemTableSeeder::class,
            ItemImagetableseeder::class,
            CommentsTableSeeder::class,
        ]);
    }
}
