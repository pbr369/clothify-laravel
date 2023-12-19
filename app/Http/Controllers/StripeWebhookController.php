<?php

namespace App\Http\Controllers;

use App\Models\StripeWebhook;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        if ($payload['type'] === 'checkout.session.completed') {
            $this->handleCheckoutSessionCompleted($payload['data']['object']);
        }

        return response()->json(['success' => true]);
    }

    private function handleCheckoutSessionCompleted($session)
    {
        $customer = \Stripe\Customer::retrieve($session['customer']);
        $items = json_decode($customer->metadata['cart'], true);

        $products = collect($items)->map(function ($item) {
            return [
                'product_id' => $item['id'],
                'quantity' => $item['cartQuantity'],
            ];
        })->toArray();

        Order::create([
            'user_id' => $customer->metadata['userId'],
            'products' => json_encode($products),
            'payment_intent_id' => $session['payment_intent'],
            'subtotal' => $session['amount_subtotal'],
            'total' => $session['amount_total'],
            'shipping' => json_encode($session['customer_details']['shipping']),
            'payment_status' => $session['payment_status'],
        ]);
    }
}
