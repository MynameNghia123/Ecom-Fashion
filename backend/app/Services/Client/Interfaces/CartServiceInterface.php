<?php

namespace App\Services\Client\Interfaces;

use Illuminate\Support\Collection;

interface CartServiceInterface
{
    /**
     * @return array{id: int, items: Collection, total_items: int, total_price: float|int}
     */
    public function getCart(int $customerId): array;

    /**
     * @return array{success: bool, message: string, data?: array}
     */
    public function addItem(int $customerId, int $variantId, int $quantity): array;

    /**
     * @return array{success: bool, message: string, data?: array}
     */
    public function updateItem(int $customerId, int $itemId, int $quantity): array;

    /**
     * @return array{success: bool, message: string, data?: array}
     */
    public function removeItem(int $customerId, int $itemId): array;

    public function syncCart(int $customerId, array $items): array;
}
