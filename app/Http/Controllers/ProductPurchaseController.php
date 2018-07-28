<?php

namespace App\Http\Controllers;

use App\Product;
use Stripe\Charge;
use Illuminate\Http\Request;

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
                'description' => $product->name,
                'customer' => $customer['id'],
                'receipt_email' => request()->user()->email,
                'metadata' => [
                    'product_id' => $product->id,
                ],
            ]);

            return $charge;
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
