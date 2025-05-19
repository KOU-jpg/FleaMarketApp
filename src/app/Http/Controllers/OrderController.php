<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Carbon\Carbon;

class OrderController extends Controller
{


    public function purchase(Item $item)
    {
        $item->load('images'); // 画像もEagerロード
        $address = Auth::user()->profile ?? null; // プロフィール住所（なければnull）
        return view('order.purchase', [
            'item' => $item,
            'address' => $address,
            
        ]);
    }
    public function updateAddress(AddressRequest $request)
    {
        $user = Auth::user();

        // プロフィールがなければ作成、あれば更新
        $validated = $request->validated();

        $profile = $user->profile ?? $user->profile()->create([]);
        $profile->postal_code = $validated['postal_code'];
        $profile->address = $validated['address'];
        $profile->building = $validated['building'] ?? '';
        $profile->save();
        $itemId = $request->input('item_id');
        
        return redirect()->route('purchase.show', ['item' => $itemId]);
    }

    public function editAddress()
    {
        $address = Auth::user()->profile ?? null;
        return view('order.edit_address', [
            'address' => $address,
        ]);
    }

    public function store(PurchaseRequest $request, Item $item)
{
    // 支払い方法を取得
    $paymentMethod = $request->input('payment_method');
        // すでに売り切れかチェック
//        if ($item->sold_at) {
  //          return redirect()->back()->with('error', 'すでに売り切れです');
    //    }

        // sold_atに現在時刻をセット
        $item->sold_at = Carbon::now();
        $item->buyer_id = Auth::id();
        $item->save();

        // サンクスページへリダイレクト
        return redirect()->route('thanks');
    }

    public function thanks()
    {
        return view('order.thanks');
    }
}
