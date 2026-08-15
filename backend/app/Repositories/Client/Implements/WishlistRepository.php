<?php

namespace App\Repositories\Client\Implements;

use App\Models\Wishlist;
use App\Repositories\Client\Interfaces\WishlistRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function __construct(
        private readonly Wishlist $model
    ) {}

    public function getByCustomerId(int $customerId): Collection
    {
        return $this->model->with([
            'product.category',
            'product.productVariants.attributeValues.attribute',
        ])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findByCustomerAndProduct(int $customerId, int $productId): ?Wishlist
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();
    }

    public function create(array $data): Wishlist
    {
        return $this->model->create($data);
    }

    public function delete(Wishlist $wishlist): void
    {
        $wishlist->delete();
    }

    public function deleteByCustomerAndProduct(int $customerId, int $productId): void
    {
        $this->model
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->delete();
    }
}
