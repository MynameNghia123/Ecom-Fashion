<?php

namespace App\Services\Client\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    public function getActiveProducts(array $filters, string $sort, int $perPage): LengthAwarePaginator;

    public function getActiveBrands(): Collection;

    public function findActiveByIdOrSlug(string $idOrSlug): ?Product;

    public function getFormattedProductDetail(string $idOrSlug): ?array;

    public function getTopRated(int $limit): Collection;
}
