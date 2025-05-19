<?php

namespace App\Http\Controllers;
use App\Models\Item;


use Illuminate\Http\Request;

use App\Models\Profile;


class AppController extends Controller
{ public function address()  { return view('order/edit_address');  }
    public function index(Request $request)
    {
        $userId = auth()->id(); // ログインユーザーのID
        $keyword = $request->input('keyword'); // ← ここでキーワード取得

        $items = Item::with('images', 'categories')
        ->where('user_id', '!=', $userId)
        ->when($keyword, function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhereHas('categories', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', "%{$keyword}%");
                  });
            });
        })
        ->latest()
        ->get();
          // お気に入り商品（マイリスト）を取得
      $mylistItems = [];
      if (auth()->check()) {
          $mylistItems = auth()->user()->favorites()->with('images')->get();
      }


        return view('main.index', compact('items', 'keyword','mylistItems'));
    }
    public function edit(){
    $user = auth()->user();
    return view('mypage.edit', compact('user'));}
    
public function mypage()
{
    $user = auth()->user();

    // 出品した商品
    $userItems = Item::where('user_id', $user->id)->with('images')->get();

    // 購入した商品（buyer_idカラムがitemsテーブルにある場合）
    $purchasedItems = Item::where('buyer_id', $user->id)->with('images')->get();

    // 3つまとめてビューに渡す
    return view('mypage.home', compact('user', 'userItems', 'purchasedItems'));
}
    
  public function sell()  { return view('main/sell');  }
  public function detail()  { return view('main/detail');}

  public function email()  {  return view('auth/email_verify');  }

  public function purchase()  { return view('order/purchase');}
}