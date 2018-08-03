<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartCheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|regex:/^(tok_)/',
        ]);

        $cart = Cart::instance('shopping');

        try {
            $customer = request()->user()->createStripeCustomer(request('token'));

            $charge = \Stripe\Charge::create([
                'amount' => $total = $cart->total(2, '', ''),
                'currency' => 'usd',
                'customer' => $customer['id'],
                'receipt_email' => $customer['email'],
            ]);

            $order = request()->user()->orders()->create([
                'charge_id' => $charge['id'],
                'charged_at' => $charge['created'],
                'charge_amount' => $total,
                'card_last_four' => $charge['source']['last4'],
            ]);

            $cart->content()->each(function ($item) use ($order) {
                $order->addProduct($item->id, $item->qty);
            });

            $cart->destroy();

            return $order;
        } catch (\Stripe\Error\Base $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong.'], 403);
        }
    }
}
