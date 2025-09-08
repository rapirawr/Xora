<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = ['Electronics', 'Weapons', 'Gadgets', 'Vehicles'];

        // Get a random existing user or create one if none exist
        $user = \App\Models\User::inRandomOrder()->first();
        if (!$user) {
            $user = \App\Models\User::factory()->create();
        }

        return [
            'name' => $this->faker->unique()->word() . ' ' . $this->faker->numberBetween(1, 10),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(50000, 1000000),
            'image_url' => 'https://picsum.photos/640/480?random=' . $this->faker->unique()->numberBetween(1, 100),
            'category' => $this->faker->randomElement($categories),
            'rating' => $this->faker->randomFloat(1, 0, 5), // Random rating between 0-5
            'sold' => $this->faker->numberBetween(0, 500), // Random sales count
            'stock' => $this->faker->numberBetween(0, 100), // Random stock between 0-100
            'user_id' => $user->id,
        ];
    }
}