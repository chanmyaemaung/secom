<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'price' => fake()->numberBetween(1000, 100000),
            'user_id' => User::first()->id,
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
        ];
    }
}
