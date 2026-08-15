<?php

namespace App\Services\Client\Interfaces;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;

interface WishlistServiceInterface
{
    /**
     * Lấy danh sách wishlist theo customer_id
     */
    public function getList(int $customerId): Collection;

    /**
     * Toggle: Thêm hoặc xóa sản phẩm khỏi wishlist
     * Trả về mảng chứa kết quả action ('added' hoặc 'removed') và dữ liệu
     */
    public function toggle(int $customerId, int $productId): array;

    /**
     * Xóa wishlist theo customer_id và product_id
     */
    public function remove(int $customerId, int $productId): void;
}
