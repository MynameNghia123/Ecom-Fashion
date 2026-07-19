<?php

namespace App\Repositories\Admin\Implements;

use App\Models\OrderDetail;
use App\Repositories\Admin\Interfaces\OrderDetailRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderDetailRepository implements OrderDetailRepositoryInterface
{
    public function __construct(
        private readonly OrderDetail $model
    ) {}

    /**
     * Lấy danh sách chi tiết sản phẩm theo ID đơn hàng.
     */
    public function getByOrderId(int $orderId): Collection
    {
        return $this->model->where('order_id', $orderId)
            ->with('productVariant')
            ->get();
    }

    /**
     * Tìm chi tiết đơn hàng theo ID.
     */
    public function findById(int $id): ?OrderDetail
    {
        return $this->model->with(['order', 'productVariant'])->find($id);
    }

    /**
     * Tạo 1 chi tiết đơn hàng.
     */
    public function create(array $data): OrderDetail
    {
        return $this->model->create($data);
    }

    /**
     * Tạo nhiều chi tiết đơn hàng cùng lúc.
     */
    public function createMany(array $detailsData): bool
    {
        return $this->model->insert($detailsData);
    }

    /**
     * Cập nhật chi tiết đơn hàng.
     */
    public function update(Model $model, array $data): OrderDetail
    {
        $model->update($data);

        return $model->fresh(['order', 'productVariant']);
    }

    /**
     * Xóa chi tiết đơn hàng.
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }
}
