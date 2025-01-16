<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Creates a new user for each order
            'total_price' => $this->faker->randomFloat(2, 10, 1000), // Random total price between 10 and 1000
            'status' => 'pending', // Default status
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
