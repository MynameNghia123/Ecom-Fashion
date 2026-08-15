<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $customerId = $request->user()->id;
        $perPage = (int) $request->query('per_page', 10);

        $notifications = $this->notificationService->getCustomerNotifications($customerId, $perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'last_page' => $notifications->lastPage(),
            ],
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $customerId = $request->user()->id;
        $count = $this->notificationService->getUnreadCount($customerId);

        return response()->json([
            'success' => true,
            'data' => ['count' => $count],
        ]);
    }

    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $customerId = $request->user()->id;
        $success = $this->notificationService->markAsRead($customerId, $id);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Đánh dấu đã đọc thành công.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy thông báo hoặc đã đọc.',
        ], 404);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $customerId = $request->user()->id;
        $count = $this->notificationService->markAllAsRead($customerId);

        return response()->json([
            'success' => true,
            'message' => "Đã đánh dấu {$count} thông báo là đã đọc.",
        ]);
    }
}
