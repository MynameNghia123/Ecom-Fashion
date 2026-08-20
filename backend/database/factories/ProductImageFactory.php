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
            'alt_text' => app(\Faker\Generator::class)->words(3, true),
            'image_url' => null, // Gán trong Seeder
            'display_order' => app(\Faker\Generator::class)->numberBetween(1, 10),
            'is_thumbnail' => false,
        ];
    }
}
