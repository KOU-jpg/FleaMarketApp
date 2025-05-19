<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 外部キー制約を一時的に無効化
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 必要なテーブルをtruncate
        \DB::table('comments')->truncate();      // ← 追加
        \DB::table('category_item')->truncate();
        \DB::table('items')->truncate();
        // 他の関連テーブルもここでtruncate

        // 外部キー制約を有効化
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // その後、各Seederを呼び出す
        $this->call([
            UserSeeder::class,
            ProfileImagesTableSeeder::class,
            ProfilesTableSeeder::class,
            ConditionsTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemTableSeeder::class,
            ItemImagetableseeder::class,
            CommentsTableSeeder::class,
        ]);
    }
}
