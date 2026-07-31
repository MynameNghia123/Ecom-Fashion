<?php

namespace App\Services\Admin\Implements;

use App\Models\ReturnRequest;
use App\Repositories\Admin\Interfaces\ReturnRequestRepositoryInterface;
use App\Services\Admin\Interfaces\OrderServiceInterface;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReturnRequestService implements ReturnRequestServiceInterface
{
    public function __construct(
        private readonly ReturnRequestRepositoryInterface $returnRequestRepository,
        private readonly OrderServiceInterface            $orderService,
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->returnRequestRepository->paginate($filters);
    }

    public function findById(int $id): ?ReturnRequest
    {
        return $this->returnRequestRepository->findById($id);
    }

    /**
     * Tạo ReturnRequest.
     *
     * Nếu client gửi lên `order_code` (string) thay vì `order_id` (integer),
     * hàm sẽ tự động resolve thông qua OrderService trước khi lưu.
     *
     * @throws ModelNotFoundException nếu order_code không tồn tại.
     */
    public function create(array $data): ReturnRequest
    {
        if (isset($data['order_code'])) {
            $order = $this->orderService->findByCode($data['order_code']);

            if (!$order) {
                throw new ModelNotFoundException("Đơn hàng với mã '{$data['order_code']}' không tồn tại.");
            }

            $data['order_id'] = $order->id;
            unset($data['order_code']);
        }

        return $this->returnRequestRepository->create($data);
    }

    public function update(Model $model, array $data): ReturnRequest
    {
        return $this->returnRequestRepository->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->returnRequestRepository->delete($model);
    }
}
