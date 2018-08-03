<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
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

    public function add(Product $product)
    {
        request()->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        Cart::instance('shopping')->add($product, (int) request('quantity'));

        return redirect()->route('cart.index');
    }

    public function index()
    {
        $items = Cart::instance('shopping')->content()->sortBy('id');

        return view('cart.index', compact('items'));
    }

    public function destroy()
    {
        Cart::instance('shopping')->destroy();

        return redirect('cart.index');
    }
}
