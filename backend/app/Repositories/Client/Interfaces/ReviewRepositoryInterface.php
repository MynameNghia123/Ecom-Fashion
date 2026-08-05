<?php
namespace App\Repositories\Client\Interfaces;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryInterface
{
    public function getByCustomerId(int $customerId): Collection;
    public function getProductReviews(int $productId): Collection;
    public function findByOrderDetailId(int $orderDetailId): ?Review;
    public function create(array $data): Review;
}
