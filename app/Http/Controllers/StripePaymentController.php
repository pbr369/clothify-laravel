<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\ShippingOption;
use App\Models\User;

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

    // Log the data
    \Log::info('Formatted Line Items:', $formattedLineItems);
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
        'success_url' => config('app.url') . '/Checkoutsuccess',
        'cancel_url' => config('app.url') . '/Allproducts',
    ]);

    return response()->json(['url' => $session->url]);
    
    }
}