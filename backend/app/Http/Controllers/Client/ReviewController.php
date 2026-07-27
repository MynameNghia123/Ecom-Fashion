<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * POST /client/reviews — Gửi đánh giá sản phẩm.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_detail_id' => 'required|integer|exists:order_details,id',
            'rating'          => 'required|integer|min:1|max:5',
            'comment'         => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min'      => 'Đánh giá tối thiểu là 1 sao.',
            'rating.max'      => 'Đánh giá tối đa là 5 sao.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $customer = Auth::user();

        $orderDetail = OrderDetail::with('order')->find($request->order_detail_id);

        if (!$orderDetail || $orderDetail->order->customer_id !== $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy chi tiết đơn hàng này.',
            ], 404);
        }

        if ($orderDetail->order->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chỉ có thể đánh giá sản phẩm của đơn hàng đã giao thành công.',
            ], 400);
        }

        $existingReview = Review::where('order_detail_id', $request->order_detail_id)->first();
        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã gửi đánh giá cho sản phẩm này trong đơn hàng rồi.',
            ], 400);
        }

        $productId = $orderDetail->productVariant->product_id;

        $review = Review::create([
            'product_id'      => $productId,
            'order_detail_id' => $request->order_detail_id,
            'customer_id'     => $customer->id,
            'rating'          => $request->rating,
            'comment'         => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã gửi đánh giá sản phẩm!',
            'data'    => $review,
        ], 201);
    }

    /**
     * GET /client/reviews — Lấy danh sách đánh giá của khách hàng hiện tại.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();

        $reviews = Review::with([
            'product',
            'orderDetail.productVariant.attributeValues.attribute'
        ])
        ->where('customer_id', $customer->id)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ]);
    }

    /**
     * GET /products/{productId}/reviews — Lấy danh sách đánh giá công khai của sản phẩm.
     */
    public function productReviews($productId): JsonResponse
    {
        $reviews = Review::with([
            'customer' => function ($q) {
                $q->select('id', 'first_name', 'last_name');
            }
        ])
        ->where('product_id', $productId)
        ->orderBy('created_at', 'desc')
        ->get();

        $avgRating = count($reviews) > 0 ? round($reviews->avg('rating'), 1) : 0;

        return response()->json([
            'success' => true,
            'data'    => $reviews,
            'average_rating' => $avgRating,
            'total_reviews'  => count($reviews),
        ]);
    }
}
