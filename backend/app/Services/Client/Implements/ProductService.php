<?php

namespace App\Services\Client\Implements;

use App\Models\Product;
use App\Repositories\Client\Interfaces\ProductRepositoryInterface;
use App\Services\Client\Interfaces\ProductServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProductService implements ProductServiceInterface
{
    public function __construct(private readonly ProductRepositoryInterface $repo) {}

    public function getActiveProducts(array $filters, string $sort, int $perPage): LengthAwarePaginator
    {
        return $this->repo->getActiveProducts($filters, $sort, $perPage);
    }

    public function getActiveBrands(): Collection
    {
        return Cache::remember('active_brands_v2', 3600, function () {
            return $this->repo->getActiveBrands();
        });
    }

    public function findActiveByIdOrSlug(string $idOrSlug): ?Product
    {
        return $this->repo->findActiveByIdOrSlug($idOrSlug);
    }

    public function getFormattedProductDetail(string $idOrSlug): ?array
    {
        $product = $this->repo->findActiveByIdOrSlug($idOrSlug);
        if (! $product) {
            return null;
        }

        $productArray = $product->toArray();
        $attributes = [];

        if ($product->productVariants) {
            foreach ($product->productVariants as $variant) {
                if ($variant->attributeValues) {
                    foreach ($variant->attributeValues as $attrValue) {
                        $attrName = $attrValue->attribute->name ?? 'Unknown';
                        $attrVal = $attrValue->value;

                        if (! isset($attributes[$attrName])) {
                            $attributes[$attrName] = [];
                        }

                        if (! in_array($attrVal, $attributes[$attrName])) {
                            $attributes[$attrName][] = $attrVal;
                        }
                    }
                }
            }
        }

        $productArray['attributes'] = $attributes;

        return $productArray;
    }

    public function getTopRated(int $limit): Collection
    {
        return Cache::remember("top_rated_products_v2_{$limit}", 3600, function () use ($limit) {
            return $this->repo->getTopRated($limit);
        });
    }

    public function getRelatedProducts(int $excludeProductId, int $categoryId, int $limit): Collection
    {
        return $this->repo->getRelatedProducts($excludeProductId, $categoryId, $limit);
    }
}
