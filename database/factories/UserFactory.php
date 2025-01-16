<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Assume that the profile photo already exists in the 'storage/app/public/profile_photo' directory

        return [
            'phone' => $this->faker->unique()->phoneNumber(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'user_type' => 'user',
            'location' => $this->faker->address(),
            'profile_photo' => 'ss',  // Generates the URL for the profile photo
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
