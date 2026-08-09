<?php

namespace App\Services\Client\Implements;

use App\Models\OrderDetail;
use App\Repositories\Client\Interfaces\ReturnRequestRepositoryInterface;
use App\Services\Client\Interfaces\ReturnRequestServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Exception;

class ReturnRequestService implements ReturnRequestServiceInterface
{
    public function __construct(
        private readonly ReturnRequestRepositoryInterface $repository
    ) {}

    public function getCustomerReturnRequests(int $customerId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getCustomerReturnRequests($customerId, $perPage);
    }

    public function getCustomerReturnRequestDetail(int $customerId, int $id): ?Model
    {
        return $this->repository->getCustomerReturnRequestDetail($customerId, $id);
    }

    public function createReturnRequest(int $customerId, array $data, array $images = []): Model
    {
        // 1. Kiểm tra OrderDetail có thuộc về User không
        $orderDetail = OrderDetail::with('order')->find($data['order_detail_id']);
        if (!$orderDetail || $orderDetail->order->customer_id !== $customerId) {
            throw new Exception('Bạn không có quyền thao tác với sản phẩm này.', 403);
        }

        // 2. Kiểm tra trạng thái đơn hàng (chỉ cho phép Delivered)
        if ($orderDetail->order->status !== 'delivered') {
            throw new Exception('Chỉ có thể yêu cầu hoàn trả cho đơn hàng đã giao thành công.', 422);
        }

        // 3. Kiểm tra hạn 7 ngày
        $deliveredAt = $orderDetail->order->updated_at; // Hoặc delivered_at nếu có
        if ($deliveredAt && $deliveredAt->diffInDays(now()) > 7) {
            throw new Exception('Đã quá hạn 7 ngày để yêu cầu hoàn trả.', 422);
        }

        // 4. Kiểm tra xem sản phẩm này đã được yêu cầu hoàn trả chưa
        $existingRequest = $this->repository->findByOrderDetailId($orderDetail->id);
        if ($existingRequest) {
            throw new Exception('Sản phẩm này đã được gửi yêu cầu hoàn trả trước đó.', 422);
        }

        // 5. Upload hình ảnh bằng chứng
        $imagePaths = [];
        foreach ($images as $file) {
            $path = $file->store('returns', 'public');
            $imagePaths[] = $path;
        }

        // 6. Tính toán refund amount dựa trên giá mua của sản phẩm
        $quantity = clone $orderDetail->quantity; 
        $refundAmount = ($orderDetail->price * $orderDetail->quantity);

        // 7. Tạo ReturnRequest
        $returnRequestData = [
            'ticket_code'       => 'RET-' . strtoupper(Str::random(8)),
            'order_id'          => $orderDetail->order_id,
            'order_detail_id'   => $orderDetail->id,
            'reason'            => $data['reason'],
            'customer_note'     => $data['customer_note'] ?? null,
            'evidence_images'   => $imagePaths,
            'quantity'          => $quantity,
            'refund_amount'     => $refundAmount,
            'status'            => 'pending',
        ];

        return $this->repository->create($returnRequestData);
    }
}
