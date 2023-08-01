<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(rand(3, 5), true);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'price' => rand(1, 100) * 1000,
            'description' => fake()->paragraphs(rand(1, 3), true),
            'status' => rand(1, count(ProductStatus::names())),
        ];
    }
}
