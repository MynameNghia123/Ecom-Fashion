<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = app(\Faker\Generator::class)->unique()->words(2, true);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => app(\Faker\Generator::class)->sentence(),
            'parent_id' => null,
        ];
    }
}
