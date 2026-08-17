<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = ['Màu sắc', 'Kích cỡ'];

        foreach ($attributes as $attr) {
            Attribute::factory()->create([
                'name' => $attr,
            ]);
        }
    }
}
