<?php

namespace App\Repositories\Client\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function getCustomerOrders(int $customerId): Collection;

    public function getCustomerOrderByCode(int $customerId, string $orderCode): ?Order;

    public function createOrder(array $data): Order;

    public function createOrderDetail(array $data): void;
}
