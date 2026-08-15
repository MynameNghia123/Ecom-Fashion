<?php

namespace App\Services\Admin\Implements;

use App\Models\Product;
use App\Repositories\Admin\Interfaces\ProductRepositoryInterface;
use App\Services\Admin\Interfaces\ProductImageServiceInterface;
use App\Services\Admin\Interfaces\ProductServiceInterface;
use App\Services\Admin\Interfaces\ProductVariantServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepo,
        private readonly ProductImageServiceInterface $imageService,
        private readonly ProductVariantServiceInterface $variantService
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->productRepo->paginate($filters);
    }

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $images = $data['images'] ?? [];
            $variants = $data['variants'] ?? [];

            unset($data['images'], $data['variants']);

            $product = $this->productRepo->create($data);

            if (! empty($images)) {
                $this->imageService->insertMany($images, $product->id);
            }

            if (! empty($variants)) {
                $this->variantService->insertMany($variants, $product->id);
            }

            return $product->load(['productImages', 'productVariants.attributeValues']);
        });
    }

    public function update(Model $model, array $data): Product
    {
        return DB::transaction(function () use ($data, $model) {
            $image = $data['images'] ?? [];
            $variants = $data['variants'] ?? [];

            unset($data['images'], $data['variants']);

            $this->productRepo->update($model, $data);

            $this->imageService->syncImages($model, $image);

            $this->variantService->syncVariants($model, $variants);

            return $model->load(['productImages', 'productVariants.attributeValues']);
        });
    }

    public function delete(Model $model): void
    {
        $this->productRepo->delete($model);
    }

    public function getStats(): array
    {
        return $this->productRepo->getStats();
    }
}
