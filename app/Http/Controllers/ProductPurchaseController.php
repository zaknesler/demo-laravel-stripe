<?php

namespace App\Http\Controllers;

use App\Product;
use Stripe\Charge;
use Illuminate\Http\Request;
use App\Billing\StripePaymentGateway;

class ProductPurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Product $product)
    {
        request()->validate([
            'token' => 'required|regex:/^(tok_)/',
        ]);

        try {
            $customer = request()->user()->createStripeCustomer(request('token'));

            $charge = Charge::create([
                'amount' => $product->price,
                'currency' => 'usd',
                'customer' => $customer['id'],
                'receipt_email' => $customer['email'],
                'metadata' => [
                    'product_id' => $product->id,
                ],
            ]);

            $order = request()->user()->orders()->create([
                'charge_id' => $charge['id'],
                'charged_at' => $charge['created'],
                'charge_amount' => $product->price,
                'card_last_four' => $charge['source']['last4'],
            ]);

            $order->products()->attach($product);

            return $order;
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        }
    }
}
