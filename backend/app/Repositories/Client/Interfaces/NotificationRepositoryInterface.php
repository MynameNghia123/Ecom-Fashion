<?php

namespace App\Repositories\Client\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getForCustomer(int $customerId, int $perPage = 10): LengthAwarePaginator;
    
    public function getUnreadCount(int $customerId): int;
    
    public function create(array $data): Model;
    
    public function markAsRead(int $customerId, int $id): bool;
    
    public function markAllAsRead(int $customerId): int;
}
