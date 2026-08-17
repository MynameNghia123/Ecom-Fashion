<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Thời trang Nam' => [
                'Áo sơ mi nam',
                'Áo thun nam',
                'Quần jean nam',
                'Quần âu nam',
            ],
            'Thời trang Nữ' => [
                'Áo sơ mi nữ',
                'Áo kiểu nữ',
                'Váy liền thân',
                'Chân váy',
            ],
            'Phụ kiện' => [
                'Đồng hồ',
                'Kính mắt',
                'Thắt lưng',
                'Túi xách',
            ]
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::factory()->create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
            ]);

            foreach ($children as $childName) {
                Category::factory()->create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
