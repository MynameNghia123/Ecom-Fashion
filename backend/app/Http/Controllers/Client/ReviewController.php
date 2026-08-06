<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Review\StoreReviewRequest;
use App\Services\Client\Interfaces\ReviewServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(private readonly ReviewServiceInterface $reviewService) {}

    /**
     * POST /client/reviews — Gửi đánh giá sản phẩm.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $customer = Auth::user();
        $validated = $request->validated();

        $result = $this->reviewService->storeReview($customer->id, $validated);

        if (!$result['success']) {
            $status = str_contains($result['message'], 'Không tìm thấy') ? 404 : 400;
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $status);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data'    => $result['data'],
        ], 201);
    }

    /**
     * GET /client/reviews — Lấy danh sách đánh giá của khách hàng hiện tại.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();

        $reviews = $this->reviewService->getCustomerReviews($customer->id);

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
        $reviews = $this->reviewService->getProductReviews($productId);

        $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;

        return response()->json([
            'success' => true,
            'data'    => $reviews,
            'average_rating' => $avgRating,
            'total_reviews'  => $reviews->count(),
        ]);
    }

    /**
     * GET /client/products/{productId}/review-eligibility
     */
    public function checkEligibility($productId): JsonResponse
    {
        $customer = Auth::user();
        $eligibility = $this->reviewService->checkReviewEligibility($customer->id, $productId);
        
        return response()->json([
            'success' => true,
            'data'    => $eligibility
        ]);
    }
}
