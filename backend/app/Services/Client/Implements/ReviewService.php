<?php

namespace App\Services\Client\Implements;

use App\Models\OrderDetail;
use App\Repositories\Client\Interfaces\ReviewRepositoryInterface;
use App\Services\Client\Interfaces\ReviewServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class ReviewService implements ReviewServiceInterface
{
    public function __construct(private readonly ReviewRepositoryInterface $repo) {}

    public function getCustomerReviews(int $customerId): Collection
    {
        return $this->repo->getByCustomerId($customerId);
    }

    public function getProductReviews(int $productId): Collection
    {
        return $this->repo->getProductReviews($productId);
    }

    public function storeReview(int $customerId, array $data): array
    {
        $orderDetail = OrderDetail::with('order')->find($data['order_detail_id']);

        if (! $orderDetail || $orderDetail->order->customer_id !== $customerId) {
            return ['success' => false, 'message' => 'Không tìm thấy chi tiết đơn hàng này.'];
        }

        if ($orderDetail->order->status !== 'completed') {
            return ['success' => false, 'message' => 'Bạn chỉ có thể đánh giá sản phẩm của đơn hàng đã giao thành công.'];
        }

        $existingReturn = \App\Models\ReturnRequest::where('order_detail_id', $data['order_detail_id'])->first();
        if ($existingReturn) {
            return ['success' => false, 'message' => 'Sản phẩm này đã tạo yêu cầu đổi/trả nên không thể gửi đánh giá.'];
        }

        $existingReview = $this->repo->findByOrderDetailId($data['order_detail_id']);
        if ($existingReview) {
            return ['success' => false, 'message' => 'Bạn đã gửi đánh giá cho sản phẩm này trong đơn hàng rồi.'];
        }

        $productId = $orderDetail->productVariant->product_id;

        $review = $this->repo->create([
            'product_id' => $productId,
            'order_detail_id' => $data['order_detail_id'],
            'customer_id' => $customerId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Cảm ơn bạn đã gửi đánh giá sản phẩm!',
            'data' => $review,
        ];
    }

    public function checkReviewEligibility(int $customerId, int $productId): array
    {
        $orderDetail = $this->repo->getEligibleOrderDetail($customerId, $productId);

        return [
            'eligible' => (bool) $orderDetail,
            'order_detail_id' => $orderDetail ? $orderDetail->id : null,
        ];
    }
}
