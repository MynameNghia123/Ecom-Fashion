<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Coupon\ApplyCouponRequest;
use App\Services\Client\Interfaces\CouponServiceInterface;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    public function __construct(private readonly CouponServiceInterface $couponService) {}

    /**
     * GET /client/coupons
     */
    public function index(): JsonResponse
    {
        $coupons = $this->couponService->getActiveCoupons();

        return response()->json([
            'success' => true,
            'data'    => $coupons,
        ]);
    }

    /**
     * POST /client/coupons/apply
     */
    public function apply(ApplyCouponRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $result = $this->couponService->applyCoupon($validated['code'], $validated['order_total']);

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 422);
        }

        return response()->json([
            'success'  => true,
            'message'  => $result['message'],
            'coupon'   => $result['coupon'],
            'discount' => $result['discount'],
        ]);
    }
}