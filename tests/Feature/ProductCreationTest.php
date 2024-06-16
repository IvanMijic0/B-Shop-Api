<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase; // Ensures the database is reset for each test

    /**
     * Test that an authorized user can successfully create a product.
     *
     * @return void
     */
    public function testUserCanCreateProduct()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $productData = [
            'name' => 'New Smartphone',
            'description' => 'Latest model with advanced features',
            'price' => 999
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201); // Assert the product creation was successful
        $response->assertJson([
            'message' => 'Product created successfully.',
            'product' => [
                'name' => 'New Smartphone',
                'description' => 'Latest model with advanced features',
                'price' => 999.99
            ]
        ]);

        $this->assertDatabaseHas('products', $productData); // Ensure data is stored in the database
    }
}
