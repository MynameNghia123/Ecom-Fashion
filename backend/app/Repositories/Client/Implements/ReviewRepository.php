<?php

namespace App\Repositories\Client\Implements;

use App\Models\OrderDetail;
use App\Models\Review;
use App\Repositories\Client\Interfaces\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(private readonly Review $model) {}

    public function getByCustomerId(int $customerId): Collection
    {
        return $this->model->with([
            'product',
            'orderDetail.productVariant.attributeValues.attribute',
        ])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getProductReviews(int $productId): Collection
    {
        return $this->model->with([
            'customer:id,first_name,last_name',
            'orderDetail.productVariant.attributeValues.attribute',
        ])
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findByOrderDetailId(int $orderDetailId): ?Review
    {
        return $this->model->where('order_detail_id', $orderDetailId)->first();
    }

    public function create(array $data): Review
    {
        return $this->model->create($data);
    }

    public function getEligibleOrderDetail(int $customerId, int $productId): ?OrderDetail
    {
        return OrderDetail::whereHas('order', function ($q) use ($customerId) {
            $q->where('customer_id', $customerId)
                ->where('status', 'completed');
        })
            ->whereHas('productVariant', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->whereDoesntHave('review')
            ->first();
    }
}
