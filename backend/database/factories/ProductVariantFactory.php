<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $price = $this->faker->numberBetween(10, 100) * 10000;
        $salePrice = $this->faker->boolean(30) ? $price * 0.8 : null;

        return [
            'product_id' => null, // Gán trong Seeder
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'price' => $price,
            'sale_price' => $salePrice,
            'cost_price' => $price * 0.6,
            'stock_quantity' => $this->faker->numberBetween(10, 100),
            'thumbnail' => null, // Gán trong Seeder
            'is_active' => true,
        ];
    }
}
