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

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->with(['customer']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('shipping_name', 'like', "%{$search}%")
                    ->orWhere('shipping_phone', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (! empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getStats(): array
    {
        return [
            'total_orders' => $this->model->count(),
            'pending' => $this->model->where('status', 'pending')->count(),
            'confirmed' => $this->model->where('status', 'confirmed')->count(),
            'shipping' => $this->model->where('status', 'shipping')->count(),
            'completed' => $this->model->where('status', 'completed')->count(),
            'cancelled' => $this->model->where('status', 'cancelled')->count(),
            'total_revenue' => (float) $this->model->where('status', '!=', 'cancelled')->sum('final_amount'),
        ];
    }

    public function findByIdWithRelations(int $id): ?Order
    {
        return $this->model->with([
            'customer',
            'coupon',
            'details.productVariant.product',
            'details.productVariant.attributeValues.attribute',
        ])->find($id);
    }

    public function findById(int $id): ?Order
    {
        return $this->model->find($id);
    }

    public function create(array $data): Order
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Order
    {
        $model->update($data);

        return $model->fresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }
}
