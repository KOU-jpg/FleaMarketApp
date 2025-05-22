<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Auth;


// メール認証付き認証ルート
Auth::routes(['verify' => true]);

// 商品一覧（トップ画面）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('items.detail');

// 会員登録
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// ログイン
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

//ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// メール認証
Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->middleware('auth')->name('verifyEmail');

//  ログイン＆メール認証済みユーザーのみアクセス可
Route::middleware(['auth', 'verified'])->group(function () {
    // マイリスト（ログインユーザーのみ）
    Route::get('/mylist', [ItemController::class, 'mylist'])->name('items.mylist');
    //コメント投稿処理
    Route::post('/comments', [ItemController::class, 'store_comment'])->name('comments.store');
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

});


//メール再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');