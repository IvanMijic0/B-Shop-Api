<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/api/register', [
            'full_name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => '+1234567890'
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'User registered successfully.'
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'johndoe',
            'email' => 'johndoe@example.com'
        ]);
    }
}
