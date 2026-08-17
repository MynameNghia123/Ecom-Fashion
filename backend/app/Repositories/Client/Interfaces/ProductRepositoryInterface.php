<?php

namespace App\Repositories\Client\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function getActiveProducts(array $filters, string $sort, int $perPage): LengthAwarePaginator;

    public function getActiveBrands(): Collection;

    public function findActiveByIdOrSlug(string $idOrSlug): ?Product;

    public function getTopRated(int $limit): Collection;

    public function getRelatedProducts(int $excludeProductId, int $categoryId, int $limit): Collection;
}
