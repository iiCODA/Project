<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(), // Creates a new order for each order item
            'product_id' => Product::factory(), // Creates a new product for each order item
            'quantity' => $this->faker->numberBetween(1, 5), // Random quantity between 1 and 5
            'price' => $this->faker->randomFloat(2, 5, 100), // Random price between 5 and 100
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
