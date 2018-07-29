<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_purchase_a_product()
    {
        $user = $this->signIn();
        $product = factory(Product::class)->create([ 'price' => 1000 ]);

        $response = $this->post('/products/1/purchase', [
            'token' => 'tok_visa',
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            'amount' => 1000,
            'status' => 'succeeded',
        ]);
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
    }
}
