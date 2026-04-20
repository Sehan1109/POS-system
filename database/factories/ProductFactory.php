<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        $costPrice = fake()->randomFloat(2, 1, 50);
        
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'barcode' => fake()->unique()->ean13(),
            'cost_price' => $costPrice,
            'selling_price' => $costPrice * 1.5, // 50% markup
            'stock_quantity' => fake()->numberBetween(10, 200),
        ];
    }
}
