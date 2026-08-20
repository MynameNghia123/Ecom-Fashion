<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => null,
            'product_variant_id' => null,
            'alt_text' => fake()->words(3, true),
            'image_url' => null, // Gán trong Seeder
            'display_order' => fake()->numberBetween(1, 10),
            'is_thumbnail' => false,
        ];
    }
}
