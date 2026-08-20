<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::whereNotNull('parent_id')->get();
        if ($categories->isEmpty()) return;

        $colorAttribute = Attribute::where('name', 'Màu sắc')->first();
        $sizeAttribute = Attribute::where('name', 'Kích cỡ')->first();

        $colors = ['Đen', 'Trắng', 'Xanh Navy', 'Be'];
        $sizes = ['S', 'M', 'L', 'XL'];

        // Thư mục chứa ảnh mẫu và thư mục đích
        $seedImagesDir = storage_path('app/public/seed_images');
        $productsDir = storage_path('app/public/products');

        if (!File::exists($productsDir)) {
            File::makeDirectory($productsDir, 0755, true);
        }

        $seedImages = [];
        if (File::exists($seedImagesDir)) {
            $files = File::allFiles($seedImagesDir);
            foreach ($files as $file) {
                if (in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                    $seedImages[] = $file->getPathname();
                }
            }
        }

        for ($i = 0; $i < 30; $i++) {
            $thumbnailPath = $this->getRandomImage($seedImages, $productsDir);

            $product = Product::factory()->create([
                'category_id' => $categories->random()->id,
                'thumbnail' => $thumbnailPath,
            ]);

            // Create product images
            $imageCount = rand(2, 4);
            for ($j = 0; $j < $imageCount; $j++) {
                ProductImage::factory()->create([
                    'product_id' => $product->id,
                    'image_url' => $this->getRandomImage($seedImages, $productsDir),
                    'display_order' => $j + 1,
                ]);
            }

            // Create variants
            $productColors = app(\Faker\Generator::class)->randomElements($colors, rand(1, 3));
            $productSizes = app(\Faker\Generator::class)->randomElements($sizes, rand(2, 4));

            foreach ($productColors as $color) {
                foreach ($productSizes as $size) {
                    $variant = ProductVariant::factory()->create([
                        'product_id' => $product->id,
                        'thumbnail' => $this->getRandomImage($seedImages, $productsDir),
                    ]);

                    if ($colorAttribute) {
                        AttributeValue::create([
                            'attribute_id' => $colorAttribute->id,
                            'product_variant_id' => $variant->id,
                            'value' => $color,
                        ]);
                    }

                    if ($sizeAttribute) {
                        AttributeValue::create([
                            'attribute_id' => $sizeAttribute->id,
                            'product_variant_id' => $variant->id,
                            'value' => $size,
                        ]);
                    }
                }
            }
        }
    }

    private function getRandomImage(array $seedImages, string $destDir): ?string
    {
        if (empty($seedImages)) {
            return null; // Trả về null nếu không có ảnh mẫu
        }

        $sourceFile = app(\Faker\Generator::class)->randomElement($seedImages);
        $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
        $newFilename = Str::uuid() . '.' . $extension;
        $destFile = $destDir . '/' . $newFilename;

        File::copy($sourceFile, $destFile);

        return 'products/' . $newFilename;
    }
}
