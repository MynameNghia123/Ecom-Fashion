<?php
namespace App\Services\Client\Interfaces;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

interface ReviewServiceInterface
{
    public function getCustomerReviews(int $customerId): Collection;
    public function getProductReviews(int $productId): Collection;
    
    /**
     * @return array{success: bool, message: string, data?: Review}
     */
    public function storeReview(int $customerId, array $data): array;
}
