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
//商品購入ページ表示
    public function show(Item $item)
    {
        $item->load('images'); 
        $address = Auth::user()->profile ?? null;
        return view('orders.show', [
            'item' => $item,
            'address' => $address,
        ]);
    }

//購入処理
    public function purchase(PurchaseRequest $request, Item $item)
    {
        // 支払い方法を取得
        $paymentMethod = $request->input('payment_method');
            // sold_atに現在時刻をセットし売り切れ状態にする
            $item->sold_at = Carbon::now();
            $item->buyer_id = Auth::id();
            $item->save();

        // 購入完了ページへリダイレクト
        return redirect()->route('thanks');
    }



//商品購入完了ページ表示
    public function thanks()
    {
        return view('order.complete');
    }

//住所変更ページ表示
public function showAddressForm(Item $item)
{
    $address = Auth::user()->profile ?? null;
    return view('orders.address', [
        'address' => $address,
        'item' => $item,
    ]);
}
//送付先住所更新処理
public function updateAddress(AddressRequest $request, Item $item)
{
    $user = Auth::user();
    $validated = $request->validated();

    $profile = $user->profile ?? $user->profile()->create([]);
    $profile->postal_code = $validated['postal_code'];
    $profile->address = $validated['address'];
    $profile->building = $validated['building'] ?? '';
    $profile->save();

    // 住所変更後に購入ページに戻す
    return redirect()->route('purchase.show', ['item' => $item->id]);
}
}
