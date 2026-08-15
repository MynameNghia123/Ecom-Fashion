<?php

namespace App\Services\Client\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface
{
    public function getCustomerOrders(int $customerId): Collection;

    public function getCustomerOrderDetails(int $customerId, string $orderCode): ?Order;

    /**
     * @return array{success: bool, message: string, data?: array, payment_url?: string|null}
     */
    public function placeOrder(int $customerId, array $data, string $clientIp): array;
}
