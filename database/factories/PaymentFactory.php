<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => $this->faker->numberBetween(1, 10),
            'payment_method' => $this->faker->randomElement(['Credit Card', 'Debit Card', 'Paypal']),
            'payment_status' => $this->faker->randomElement(['Pending', 'Processing', 'Completed', 'Failed']),
            'payment_amount' => $this->faker->randomFloat(2, 10, 1000),
            'buyer_id' => $this->faker->numberBetween(1, 10),
            'seller_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
