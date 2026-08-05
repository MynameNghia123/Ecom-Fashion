<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AiChat\ChatRequest;
use App\Http\Requests\Client\AiChat\SyncGuestHistoryRequest;
use App\Services\Client\Interfaces\AiChatServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiChatController extends Controller
{
    public function __construct(private readonly AiChatServiceInterface $aiChatService) {}

    /**
     * POST /api/client/ai/chat
     * Xử lý câu hỏi chat của khách hàng (lưu DB nếu đã login)
     */
    public function chat(ChatRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $request->user('sanctum');
        $customerId = $user ? $user->id : null;

        $result = $this->aiChatService->chat(
            $validated['messages'],
            $validated['product_id'] ?? null,
            $customerId
        );

        if (!$result['success']) {
            return response()->json($result, $result['code'] ?? 502);
        }

        return response()->json([
            'success' => true,
            'reply' => $result['reply']
        ]);
    }

    /**
     * GET /api/client/ai/history
     * Lấy lịch sử chat từ DB dành cho Customer đã đăng nhập
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $messages = $this->aiChatService->getHistory($user->id);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * POST /api/client/ai/sync-guest-history
     * Đồng bộ lịch sử chat từ Cookie của khách vào DB khi họ vừa Đăng nhập
     */
    public function syncGuestHistory(SyncGuestHistoryRequest $request): JsonResponse
    {
        $user = $request->user('sanctum');
        $validated = $request->validated();

        $this->aiChatService->syncGuestHistory($user->id, $validated['messages']);

        return response()->json([
            'success' => true,
            'message' => 'Đồng bộ lịch sử chat thành công!'
        ]);
    }
}
