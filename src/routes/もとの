<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;


// 認証関連
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register_user']);
Route::post('/login', [AuthController::class, 'login_user']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 認証必須ルート
Route::middleware('auth')->group(function () {
    Route::get('/', [AppController::class, 'index'])->name('index');
});

// その他のルート（必要に応じて追加）
Route::get('address', [AppController::class, 'address']);
Route::get('mypage', [AppController::class, 'mypage'])->name('mypage');
Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
Route::post('edit', [ProfileController::class, 'edit_profile']);


// 出品ページ
Route::get('sell', [AppController::class, 'sell'])->name('sell');
Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
Route::post('/favorite/toggle', [FavoriteController::class, 'toggle']);




// 配送先住所編集
Route::get('/address/edit', [OrderController::class, 'editAddress'])->name('address.edit');
Route::post('/address/update', [OrderController::class, 'updateAddress'])->name('address.update');




// 商品詳細表示（コメント付き）
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::post('/comments', [ItemController::class, 'store_comment'])->name('comments.store')->middleware('auth');
Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

// 購入確認画面（GET）
Route::get('/purchase/{item}', [OrderController::class, 'purchase'])->name('purchase.show');

// 購入処理（POST）
Route::post('/purchase/{item}', [OrderController::class, 'store'])->name('purchase.store');

// サンクスページ
Route::get('/order/thanks', [OrderController::class, 'thanks'])->name('thanks');