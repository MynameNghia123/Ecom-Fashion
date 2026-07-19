<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Order;
use App\Repositories\Admin\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly Order $model
    ) {}

    /**
     * Phân trang danh sách đơn hàng kèm lọc dữ liệu.
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->query()->with(['customer', 'coupon', 'orderDetails.productVariant']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('order_code', 'like', "%{$filters['search']}%")
                  ->orWhere('shipping_name', 'like', "%{$filters['search']}%")
                  ->orWhere('shipping_phone', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        return $query->orderBy('id', 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Tìm đơn hàng theo ID kèm quan hệ chi tiết.
     */
    public function findById(int $id): ?Order
    {
        return $this->model->with(['customer', 'coupon', 'orderDetails.productVariant'])->find($id);
    }

    /**
     * Tìm đơn hàng theo Mã Đơn Hàng.
     */
    public function findByCode(string $code): ?Order
    {
        return $this->model->with(['customer', 'coupon', 'orderDetails.productVariant'])->where('order_code', $code)->first();
    }

    /**
     * Tạo mới đơn hàng.
     */
    public function create(array $data): Order
    {
        return $this->model->create($data);
    }

    /**
     * Cập nhật thông tin đơn hàng.
     */
    public function update(Model $model, array $data): Order
    {
        $model->update($data);

        return $model->fresh(['customer', 'coupon', 'orderDetails.productVariant']);
    }

    /**
     * Xóa đơn hàng.
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }
}
