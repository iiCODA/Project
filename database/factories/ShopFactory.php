<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Creates a new user for each shop
            'name' => $this->faker->company(),
            'location' => $this->faker->address(),
            'shop_photo' => $this->faker->imageUrl(200, 200, 'business'), // Generates a placeholder image URL
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
    