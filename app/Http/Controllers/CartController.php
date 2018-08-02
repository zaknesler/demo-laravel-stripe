<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function add(Product $product, $quantity = 1)
    {
        Cart::instance('shopping')->add($product, $quantity);

        Cart::instance('shopping')->store(auth()->id());
    }

    public function index()
    {
        $this->add(Product::first(), 1);
        $items = Cart::instance('shopping')->content();

        dd($items);

        return view('cart.index');
    }
}
