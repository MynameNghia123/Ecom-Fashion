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
        $name = $this->faker->unique()->words(3, true);
        return [
            'category_id' => null, // Sẽ được gán trong Seeder
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => '<p>' . implode('</p><p>', $this->faker->paragraphs(3)) . '</p>',
            'brand' => $this->faker->company(),
            'thumbnail' => null, // Sẽ được gán trong Seeder
            'user_manual' => $this->faker->text(),
            'is_active' => true,
        ];
    }
}
