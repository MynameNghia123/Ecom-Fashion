<?php
namespace App\Services\Client\Implements;
use App\Models\Product;
use App\Repositories\Client\Interfaces\ProductRepositoryInterface;
use App\Services\Client\Interfaces\ProductServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService implements ProductServiceInterface
{
    public function __construct(private readonly ProductRepositoryInterface $repo) {}

    public function getActiveProducts(array $filters, string $sort, int $perPage): LengthAwarePaginator
    {
        return $this->repo->getActiveProducts($filters, $sort, $perPage);
    }

    public function getActiveBrands(): Collection
    {
        return $this->repo->getActiveBrands();
    }

    public function findActiveByIdOrSlug(string $idOrSlug): ?Product
    {
        return $this->repo->findActiveByIdOrSlug($idOrSlug);
    }
}
