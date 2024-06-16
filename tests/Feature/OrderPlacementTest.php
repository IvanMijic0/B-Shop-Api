<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPlacementTest extends TestCase
{
    use RefreshDatabase; // Ensures the database is reset for each test

    /**
     * Test that an authenticated user can successfully place an order.
     *
     * @return void
     */
    public function testUserCanPlaceOrder()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Gadget',
            'price' => 299.99
        ]);

        $this->actingAs($user); // Authenticate as the user

        $orderData = [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'order_status' => 'pending',
            'tracking_status' => 'processing'
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(201); // Assert the order placement was successful
        $response->assertJson([
            'message' => 'Order placed successfully.',
            'order' => [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'order_status' => 'pending',
                'tracking_status' => 'processing'
            ]
        ]);

        $this->assertDatabaseHas('orders', $orderData); // Ensure order data is stored in the database
        $product->refresh();
        $this->assertEquals(8, $product->stock); // Ensure stock is decremented
    }
}
