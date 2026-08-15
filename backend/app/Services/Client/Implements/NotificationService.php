<?php

namespace App\Services\Client\Implements;

use App\Repositories\Client\Interfaces\NotificationRepositoryInterface;
use App\Services\Client\Interfaces\NotificationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private readonly NotificationRepositoryInterface $repository
    ) {}

    public function getCustomerNotifications(int $customerId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getForCustomer($customerId, $perPage);
    }

    public function getUnreadCount(int $customerId): int
    {
        return $this->repository->getUnreadCount($customerId);
    }

    public function notify(int $customerId, string $type, string $title, string $content): void
    {
        $this->repository->create([
            'customer_id' => $customerId,
            'type' => $type,
            'title' => $title,
            'content' => $content,
            'is_read' => false,
        ]);
    }

    public function markAsRead(int $customerId, int $id): bool
    {
        return $this->repository->markAsRead($customerId, $id);
    }

    public function markAllAsRead(int $customerId): int
    {
        return $this->repository->markAllAsRead($customerId);
    }
}
