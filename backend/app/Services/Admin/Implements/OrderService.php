<?php

namespace App\Services\Admin\Implements;

use App\Models\Order;
use App\Repositories\Admin\Interfaces\OrderRepositoryInterface;
use App\Services\Admin\Interfaces\OrderServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->orderRepository->paginate($filters);
    }

    public function getStats(): array
    {
        return $this->orderRepository->getStats();
    }

    public function getDetail(int $id): ?Order
    {
        return $this->orderRepository->findByIdWithRelations($id);
    }

    public function create(array $data): Model
    {
        return $this->orderRepository->create($data);
    }

    public function update(Model $model, array $data): Order
    {
        try {
            DB::beginTransaction();

            $oldStatus = $model->status;
            $newStatus = $data['status'] ?? $oldStatus;

            // Load details for stock logic if not loaded
            $model->load('details.productVariant');

            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($model->details as $detail) {
                    if ($detail->productVariant) {
                        $detail->productVariant->increment('stock_quantity', $detail->quantity);
                    }
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($model->details as $detail) {
                    if ($detail->productVariant) {
                        if ($detail->productVariant->stock_quantity < $detail->quantity) {
                            throw new Exception("Sản phẩm '{$detail->productVariant->sku}' không đủ tồn kho để khôi phục đơn hàng.");
                        }
                        $detail->productVariant->decrement('stock_quantity', $detail->quantity);
                    }
                }
            }

            $updated = $this->orderRepository->update($model, $data);

            $updated->load([
                'customer',
                'coupon',
                'details.productVariant.product',
                'details.productVariant.attributeValues.attribute'
            ]);

            DB::commit();

            return $updated;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Model $model): void
    {
        $this->orderRepository->delete($model);
    }
}
