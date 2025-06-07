<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Item;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;

class StripePaymentController extends Controller
{
//stripeによる購入処理
public function checkout(PurchaseRequest $request, $item_id)
{
    $item = Item::findOrFail($item_id);
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    $stripe_payment_method = $request->payment_method === 'convenience' ? 'konbini' : 'card';

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => [$stripe_payment_method], // 選択肢を一つだけに
        'line_items' => [[
            'price_data' => [
                'currency' => 'jpy',
                'unit_amount' => $item->price,
                'product_data' => [
                    'name' => $item->name,
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        // 支払い方法をパラメータで渡す
        // $request->payment_method,
        'success_url' => route('items.index'),
        'cancel_url' => route('purchase.cancel', ['item_id' => $item->id]),
        'metadata' => [
            'item_id' => $item->id,
            'user_id' => $request->user()->id,
            'payment_method' => $request->payment_method,
        ],
    ]);

    return redirect($session->url);
}


public function cancel(Request $request)
{    // 直前の購入商品IDを取得（セッションやリクエストから渡すなど工夫が必要）
    $item_id = $request->get('item_id'); // 例: クエリパラメータで渡す場合
    // エラーメッセージをフラッシュして購入画面へリダイレクト
    return redirect()->route('purchase.show', ['item_id' => $item_id])
        ->with('error', '決済がキャンセルされました。もう一度お試しください。');
}
}
