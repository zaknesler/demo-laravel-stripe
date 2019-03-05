<?php

namespace Tests\Feature;

use App\Order;
use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_add_a_product_to_their_cart()
    {
        $user = $this->signIn();
        $product = factory(Product::class)->create([ 'price' => 1000 ]);

        $this->post('/cart/add/1', [
            'quantity' => 3,
        ])->assertRedirect('/cart');

        $response = $this->post('/cart/checkout', [
            'token' => 'tok_visa',
        ]);

        $response->assertJson([
            'id' => 1,
            'user_id' => 1,
            'charge_amount' => 3000,
        ]);

        $this->assertEquals(1, Order::count());
    }

    /** @test */
    function a_user_cannot_purchase_a_product_with_an_invalid_card()
    {
        $user = $this->signIn();
        $product = factory(Product::class)->create([ 'price' => 1000 ]);

        $response = $this->post('/products/1/purchase', [
            'token' => 'tok_chargeDeclined',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Your card was declined.',
        ]);
        $this->assertEquals(0, Order::count());
    }
}
