<?php

namespace App\Services\Client\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationServiceInterface
{
    public function getCustomerNotifications(int $customerId, int $perPage = 10): LengthAwarePaginator;

    public function getUnreadCount(int $customerId): int;

    public function notify(int $customerId, string $type, string $title, string $content): void;

    public function markAsRead(int $customerId, int $id): bool;

    public function markAllAsRead(int $customerId): int;
}
