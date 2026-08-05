<?php
namespace App\Repositories\Client\Interfaces;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;

interface WishlistRepositoryInterface
{
    /**
     * Lấy danh sách wishlist theo customer_id
     */
    public function getByCustomerId(int $customerId): Collection;

    /**
     * Tìm một record wishlist theo customer_id và product_id
     */
    public function findByCustomerAndProduct(int $customerId, int $productId): ?Wishlist;

    /**
     * Thêm mới một wishlist
     */
    public function create(array $data): Wishlist;

    /**
     * Xóa một wishlist record
     */
    public function delete(Wishlist $wishlist): void;

    /**
     * Xóa wishlist dựa vào customer_id và product_id
     */
    public function deleteByCustomerAndProduct(int $customerId, int $productId): void;
}
