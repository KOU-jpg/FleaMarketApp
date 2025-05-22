<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellController;


// 商品一覧（トップ画面）

Route::get('/', [ItemController::class, 'index'])->name('items.index');


// 会員登録
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// ログイン
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

//ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// メール認証（認証必須 ※トークン等で判定）
Route::get('/verify-email', [AuthController::class, 'verifyEmail']);

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('items.detail');

// 商品購入（認証必須）
Route::middleware('auth')->group(function () {
    // マイリスト（ログインユーザーのみ）
    Route::get('/mylist', [ItemController::class, 'mylist'])->name('items.mylist');
    //コメント投稿処理
    Route::post('/comments', [ItemController::class, 'store_comment'])->name('comments.store')->middleware('auth');
    //お気に入り保存処理
    Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    // 商品購入画面
    Route::get('/purchase/{item_id}', [OrderController::class, 'show'])->name('purchase.show');
    //購入処理
    Route::post('/purchase/{item_id}', [OrderController::class, 'purchase'])->name('purchase');
    Route::get('/thanks', [OrderController::class, 'thanks'])->name('thanks');

    // 住所変更ページ
    Route::get('/purchase/address/{item_id}', [OrderController::class, 'showAddressForm'])->name('address.form');
    Route::post('/purchase/address/{item_id}', [OrderController::class, 'updateAddress'])->name('address.update');

    // 商品出品
    Route::get('/sell', [SellController::class, 'showForm'])->name('sell.form');
    Route::post('/sell', [SellController::class, 'sell'])->name('sell');

    // プロフィール画面
    Route::get('/mypage', [UserController::class, 'show'])->name('mypage');

    // プロフィール編集
    Route::get('/mypage/profile', [UserController::class, 'editProfile'])->name('mypage.profile.edit');
    Route::post('/mypage/profile', [UserController::class, 'updateProfile'])->name('mypage.profile.update');

    // プロフィール画面_購入した商品一覧
    //Route::get('/mypage', [UserController::class, 'buyList'])
    //    ->name('mypage.buy')
    //    ->where('tab', 'buy')
    //    ->defaults('tab', 'buy');

    // プロフィール画面_出品した商品一覧
    //Route::get('/mypage', [UserController::class, 'sellList'])
    //    ->name('mypage.sell')
    //    ->where('tab', 'sell')
    //    ->defaults('tab', 'sell');
});
