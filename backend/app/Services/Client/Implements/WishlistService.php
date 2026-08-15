<?php

namespace App\Services\Client\Implements;

use App\Repositories\Client\Interfaces\WishlistRepositoryInterface;
use App\Services\Client\Interfaces\WishlistServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class WishlistService implements WishlistServiceInterface
{
    public function __construct(
        private readonly WishlistRepositoryInterface $repo
    ) {}

    public function getList(int $customerId): Collection
    {
        return $this->repo->getByCustomerId($customerId);
    }

    public function toggle(int $customerId, int $productId): array
    {
        $existing = $this->repo->findByCustomerAndProduct($customerId, $productId);

        if ($existing) {
            $this->repo->delete($existing);

            return [
                'action' => 'removed',
                'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích',
                'data' => null,
            ];
        }

        $wishlist = $this->repo->create([
            'customer_id' => $customerId,
            'product_id' => $productId,
            'created_at' => now(),
        ]);

        return [
            'action' => 'added',
            'message' => 'Đã thêm sản phẩm vào danh sách yêu thích',
            'data' => $wishlist,
        ];
    }

    public function remove(int $customerId, int $productId): void
    {
        $this->repo->deleteByCustomerAndProduct($customerId, $productId);
    }
}
