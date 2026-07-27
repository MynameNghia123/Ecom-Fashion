<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * GET /admin/reviews — Lấy danh sách đánh giá của hệ thống (phân trang & bộ lọc).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with([
            'customer' => function ($q) {
                $q->select('id', 'first_name', 'last_name', 'email');
            },
            'product' => function ($q) {
                $q->select('id', 'name', 'thumbnail');
            },
            'orderDetail.productVariant.attributeValues.attribute'
        ]);

        // Lọc theo rating (số sao)
        if ($request->has('rating') && $request->rating !== '') {
            $query->where('rating', intval($request->rating));
        }

        // Tìm kiếm theo nội dung comment, tên sản phẩm, tên khách hàng
        if ($request->has('search') && $request->search !== '') {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', $search)
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', $search);
                  })
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search);
                  });
            });
        }

        $perPage = intval($request->input('per_page', 10));
        $reviews = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Tính toán các thống kê tổng quát
        $totalReviews = Review::count();
        $averageRating = Review::avg('rating') ?: 0;
        
        $starStats = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::where('rating', $i)->count();
            $starStats[$i] = [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) . '%' : '0%'
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $reviews->items(),
            'meta'    => [
                'current_page' => $reviews->currentPage(),
                'per_page'     => $reviews->perPage(),
                'total'        => $reviews->total(),
                'last_page'    => $reviews->lastPage(),
                'average'      => round($averageRating, 1),
                'star_stats'   => $starStats
            ]
        ]);
    }

    /**
     * DELETE /admin/reviews/{id} — Xóa nhận xét (Spam / Vi phạm).
     */
    public function destroy($id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá này.',
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa đánh giá thành công.',
        ]);
    }
}
