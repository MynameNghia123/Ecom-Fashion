<?php

namespace App\Repositories\Client\Implements;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\Client\Interfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(private readonly Order $model, private readonly OrderDetail $detailModel) {}

    public function getCustomerOrders(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getCustomerOrderByCode(int $customerId, string $orderCode): ?Order
    {
        return $this->model->with([
            'details.productVariant.product',
            'details.productVariant.attributeValues.attribute',
            'details.review',
        ])
            ->where('order_code', $orderCode)
            ->where('customer_id', $customerId)
            ->first();
    }

    public function createOrder(array $data): Order
    {
        return $this->model->create($data);
    }

    public function createOrderDetail(array $data): void
    {
        $this->detailModel->create($data);
    }
}
