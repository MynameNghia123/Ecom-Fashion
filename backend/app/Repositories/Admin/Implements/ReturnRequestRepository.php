<?php

namespace App\Repositories\Admin\Implements;

use App\Models\ReturnRequest;
use App\Repositories\Admin\Interfaces\ReturnRequestRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ReturnRequestRepository implements ReturnRequestRepositoryInterface
{
    public function __construct(
        private readonly ReturnRequest $model
    ) {}

    /**
     * Phân trang danh sách yêu cầu trả hàng có lọc dữ liệu.
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->query()->with(['order.customer', 'processedByStaff', 'orderDetails']);

        if (!empty($filters['search'])) {
            $query->whereHas('order', function ($q) use ($filters) {
                $q->where('order_code', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        return $query->orderBy('id', 'desc')
            ->paginate($filters['per_page'] ?? 4);
    }

    public function findById(int $id): ?ReturnRequest
    {
        return $this->model->with(['order.customer', 'processedByStaff', 'orderDetails.productVariant'])->find($id);
    }

    public function create(array $data): ReturnRequest
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): ReturnRequest
    {
        $model->update($data);

        return $model->fresh(['order.customer', 'processedByStaff', 'orderDetails']);
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }
}
