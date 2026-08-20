<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = app(\Faker\Generator::class)->unique()->words(3, true);
        return [
            'category_id' => null, // Sẽ được gán trong Seeder
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => '<p>' . implode('</p><p>', app(\Faker\Generator::class)->paragraphs(3)) . '</p>',
            'brand' => app(\Faker\Generator::class)->company(),
            'thumbnail' => null, // Sẽ được gán trong Seeder
            'user_manual' => app(\Faker\Generator::class)->text(),
            'is_active' => true,
        ];
    }
}
