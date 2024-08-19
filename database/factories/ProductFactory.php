<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'quantity' => fake()->numberBetween(1, 10),
            'price' => fake()->numberBetween(100, 1000),
            'description' => fake()->text(),
            'published' => fake()->boolean(),
            'in_stock' => fake()->boolean(),
            'brand_id' => 1,
            'category_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
