<?php
namespace App\Repositories\Client\Implements;
use App\Models\Product;
use App\Repositories\Client\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private readonly Product $model) {}

    public function getActiveProducts(array $filters, string $sort, int $perPage): LengthAwarePaginator
    {
        $query = $this->model->where('is_active', true)
            ->with(['category', 'productImages', 'productVariants.attributeValues.attribute']);

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['category_slug'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('slug', $filters['category_slug']);
            });
        }

        if (!empty($filters['search'])) {
            $s = trim($filters['search']);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%")
                  ->orWhereHas('category', function ($cq) use ($s) {
                      $cq->where('name', 'like', "%{$s}%");
                  })
                  ->orWhereHas('productVariants', function ($vq) use ($s) {
                      $vq->where('sku', 'like', "%{$s}%");
                  });
            });
        }

        if (!empty($filters['brand'])) {
            $query->where('brand', $filters['brand']);
        }

        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $minPrice = isset($filters['min_price']) ? (float) $filters['min_price'] : 0;
            $maxPrice = isset($filters['max_price']) ? (float) $filters['max_price'] : PHP_INT_MAX;

            $query->whereHas('productVariants', function ($q) use ($minPrice, $maxPrice) {
                $q->whereRaw('COALESCE(sale_price, price) >= ?', [$minPrice])
                  ->whereRaw('COALESCE(sale_price, price) <= ?', [$maxPrice]);
            });
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('(
                    SELECT MIN(COALESCE(sale_price, price))
                    FROM product_variants
                    WHERE product_variants.product_id = products.id
                ) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('(
                    SELECT MIN(COALESCE(sale_price, price))
                    FROM product_variants
                    WHERE product_variants.product_id = products.id
                ) DESC');
                break;
            default: // 'latest'
                $query->latest();
                break;
        }

        return $query->paginate($perPage);
    }

    public function getActiveBrands(): Collection
    {
        return $this->model->where('is_active', true)
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();
    }

    public function findActiveByIdOrSlug(string $idOrSlug): ?Product
    {
        return $this->model->where('is_active', true)
            ->with(['category', 'productImages', 'productVariants.attributeValues.attribute'])
            ->where(function ($query) use ($idOrSlug) {
                $query->where('id', $idOrSlug)->orWhere('slug', $idOrSlug);
            })
            ->first();
    }
}
