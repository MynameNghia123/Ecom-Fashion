<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\Interfaces\ReviewServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewServiceInterface $service
    ) {}

    /**
     * GET /admin/reviews — Lấy danh sách đánh giá của hệ thống (phân trang & bộ lọc).
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'rating' => $request->query('rating'),
            'search' => $request->query('search'),
            'per_page' => (int) $request->input('per_page', 10),
        ];

        $reviews = $this->service->getList($filters);
        $stats = $this->service->getStats();

        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'last_page' => $reviews->lastPage(),
                'average' => $stats['average'],
                'star_stats' => $stats['star_stats'],
            ],
        ]);
    }

    /**
     * DELETE /admin/reviews/{id} — Xóa nhận xét (Spam / Vi phạm).
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->service->deleteReview($id);

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đánh giá thành công.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
