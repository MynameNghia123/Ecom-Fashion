<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * GET /client/coupons
     */
    public function index(): JsonResponse
    {
        $coupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('max_usage')
                  ->orWhereColumn('used_count', '<', 'max_usage');
            })
            ->orderBy('expiry_date')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $coupons,
        ]);
    }

    /**
     * POST /client/coupons/apply
     */
    public function apply(Request $request): JsonResponse
    {
        $request->validate([
            'code'        => 'required|string',
            'order_total' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now()->toDateString());
            })
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Ma giam gia khong hop le hoac da het han.'], 422);
        }

        if ($coupon->max_usage && $coupon->used_count >= $coupon->max_usage) {
            return response()->json(['success' => false, 'message' => 'Ma giam gia da het luot su dung.'], 422);
        }

        if ($coupon->price_min_order_value && $request->order_total < $coupon->price_min_order_value) {
            return response()->json(['success' => false, 'message' => 'Don hang chua dat gia tri toi thieu.'], 422);
        }

        $discount = $coupon->type === 'percentage'
            ? round($request->order_total * $coupon->discount_value / 100)
            : $coupon->discount_value;

        return response()->json([
            'success'  => true,
            'message'  => 'Ap dung ma giam gia thanh cong!',
            'coupon'   => $coupon,
            'discount' => $discount,
        ]);
    }
}