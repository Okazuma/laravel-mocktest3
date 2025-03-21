<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Item;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $itemName = $request->item_name;
        $item = Item::find($request->item_id);
        $amount = $item->price;

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $itemName,
                    ],
                    'unit_amount' => $amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'metadata' => [
            'item_id' => $request->item_id,
            'payment_method_id' => $request->payment_method_id,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            ],
        ]);
        return redirect()->away($session->url);
    }

}
