<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('buyer_id')->nullable()->constrained('users'); 
    $table->foreignId('condition_id')->constrained();
    $table->string('name');
    $table->text('description');
    $table->string('brand')->nullable();
    $table->decimal('price', 10, 2); // 整数部8桁＋小数部2桁
    $table->timestamp('sold_at')->nullable();
    $table->unsignedInteger('favorite_count')->default(0); // 星マーク（お気に入り）
    $table->unsignedInteger('like_count')->default(0);     // ハートマーク（いいね）
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
