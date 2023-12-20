<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\ShippingOption;
use App\Models\User;
use App\Models\Orders;


class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
    Stripe::setApiKey(config('stripe.sk'));

    $lineItems = $request->input('cartItems');
    $customer_id = $request->input('customer_id');

    $customer = User::find($customer_id);


    $formattedLineItems = [];

    foreach ($lineItems as $item) {
        $formattedLineItems[] = [
          'price_data' => [
               'currency' => 'php',
               'product_data' => [
                'name' => $item['brand'] . ' / ' . $item['product_name'],
                'images' => [$item['image_url_1']],
            ],
            'unit_amount' => round($item['price'] * 100), 
        ],
        'quantity' => $item['amount'], 
    ];
    }
    $shippingRates = [["next_day_air", 50000], ["business_day", 0]];

    $session = Session::create([
        'payment_method_types' => ['card'],
        'shipping_address_collection' => [
            'allowed_countries' => ['US', 'CA', 'PH'],
        ],
        'shipping_options' => [
    [
      'shipping_rate_data' => [
        'type' => 'fixed_amount',
        'fixed_amount' => [
          'amount' => 0,
          'currency' => 'php',
        ],
        'display_name' => 'Free Shipping',
        'delivery_estimate' => [
          'minimum' => [
            'unit' => 'business_day',
            'value' => 5,
          ],
          'maximum' => [
            'unit' => 'business_day',
            'value' => 7,
          ],
        ],
      ],
    ],
    [
      'shipping_rate_data' => [
        'type' => 'fixed_amount',
        'fixed_amount' => [
          'amount' => 50000,
          'currency' => 'php',
        ],
        'display_name' => 'Next Day Delivery',
        'delivery_estimate' => [
          'minimum' => [
            'unit' => 'business_day',
            'value' => 1,
          ],
          'maximum' => [
            'unit' => 'business_day',
            'value' => 1,
          ],
        ],
      ],
    ],
  ],
        'phone_number_collection' => [
            'enabled' => true,
        ],
        'line_items' => $formattedLineItems,
        'mode' => 'payment',
        'customer' => $customer->stripe_customer_id,
        'success_url' => config('app.url') . ':5173' . '/Checkoutsuccess',
        'cancel_url' => config('app.url') . ':5173' . '/Allproducts',
    ]);


    
    $getProdID = [];

    foreach ($lineItems as $item) {
      $getProdID[] = [
        'product_id' => $item['id'],
      ];
    }

$productIdsArray = array_column($getProdID, 'product_id');
$productIdsString = implode(',', $productIdsArray);

    $subtotal = 0;
foreach ($formattedLineItems as $item) {
    $subtotal += $item['price_data']['unit_amount'] * $item['quantity'] / 100;
}

// Calculate shipping cost
$shippingCost = 0;

foreach ($session->shipping_options as $shippingOption) {
    if ($shippingOption['selected']) {
        $shippingCost = $shippingOption['amount']['value'] / 100;
        break;
    }
}

// Calculate total including shipping cost
$total = $subtotal + $shippingCost;

$productNames = [];
foreach ($formattedLineItems as $item) {
    $productNames[] = $item['price_data']['product_data']['name'];
}

        $order = new Orders();
        $order->user_id = $customer_id;
        $order->status = 'toship';
        $order->product_name = implode(', ', $productNames);
        $order->product_id = (int) $productIdsArray[0];
        $order->subtotal = $subtotal;
        $order->total = $total;
        $order->payment_status = 'Paid';
        $order->save();

    // if ($session->status == 'paid') {
    //   \Log::info('Payment status is "paid".');
    //     $order = new Orders();
    //     $order->user_id = $customer_id;
    //     $order->status = 'To Ship';
    //     $order->product_name = implode(', ', $productNames);
    //     $order->product_id = (int) $productIdsArray[0];
    //     $order->subtotal = $subtotal;
    //     $order->total = $subtotal;
    //     $order->payment_status = 'Paid';
    //     $order->save();
    //     \Log::info('Order saved successfully.');
    // } else {
    // \Log::info('Payment status is not "paid".');
    // }

    return response()->json(['url' => $session->url]);

    }
}