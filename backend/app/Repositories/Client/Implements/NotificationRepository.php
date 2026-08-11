<?php

namespace App\Repositories\Client\Implements;

use App\Models\Notification;
use App\Repositories\Client\Interfaces\NotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getForCustomer(int $customerId, int $perPage = 10): LengthAwarePaginator
    {
        return Notification::where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getUnreadCount(int $customerId): int
    {
        return Notification::where('customer_id', $customerId)
            ->where('is_read', false)
            ->count();
    }

    public function create(array $data): Model
    {
        return Notification::create($data);
    }

    public function markAsRead(int $customerId, int $id): bool
    {
        $notification = Notification::where('customer_id', $customerId)
            ->where('id', $id)
            ->first();
            
        if ($notification && !$notification->is_read) {
            $notification->update(['is_read' => true]);
            return true;
        }
        
        return false;
    }

    public function markAllAsRead(int $customerId): int
    {
        return Notification::where('customer_id', $customerId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
