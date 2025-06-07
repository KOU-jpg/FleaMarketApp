<?php

namespace App\Http\Controllers;


// StripeWebhookController.php
use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Item;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // チェックアウトセッション完了時
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            
            $item = Item::where('stripe_payment_intent_id', $session->payment_intent)->first();
            
            if ($item) {
                // 支払い方法で分岐
                if ($session->payment_method_types[0] === 'card') {
                    $item->update([
                        'sold_at' => now(),
                        'payment_status' => 'paid',
                        'payment_expiry' => null
                    ]);
                } elseif ($session->payment_method_types[0] === 'konbini') {
                    $item->update([
                        'payment_status' => 'pending',
                        'payment_expiry' => now()->addDays(3)
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
