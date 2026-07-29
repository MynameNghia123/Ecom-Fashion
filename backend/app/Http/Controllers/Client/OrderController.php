<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use App\Services\VNPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct(private readonly VNPayService $vnpay)
    {
    }

    /**
     * POST /client/orders
     *
     * Body:
     * {
     *   "shipping_name": "...",
     *   "shipping_phone": "...",
     *   "shipping_address": "...",
     *   "shipping_fee": 0,
     *   "payment_method": "cod" | "vnpay",
     *   "items": [
     *     { "product_variant_id": 1, "quantity": 2 }
     *   ],
     *   "coupon_code": null
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_fee' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cod,vnpay',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'coupon_code' => 'nullable|string',
        ]);

        $customer = Auth::user();

        try {
            DB::beginTransaction();

            // ── 1. Tính toán giá trị đơn hàng ──────────────────────────────
            $subTotal = 0;
            $orderItems = [];

            foreach ($request->items as $cartItem) {
                $variant = ProductVariant::findOrFail($cartItem['product_variant_id']);

                if ($variant->stock_quantity < $cartItem['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Sản phẩm '{$variant->sku}' không đủ số lượng tồn kho.",
                    ], 422);
                }

                $unitPrice = $variant->sale_price ?? $variant->price;
                $subTotal += $unitPrice * $cartItem['quantity'];

                $orderItems[] = [
                    'variant' => $variant,
                    'quantity' => $cartItem['quantity'],
                    'unit_price' => $unitPrice,
                    'cost_price' => $variant->cost_price ?? 0,
                ];
            }

            // Xử lý Coupon
            $couponId = null;
            $discountAmount = 0;
            $appliedCoupon = null;

            if (!empty($request->coupon_code)) {
                $appliedCoupon = \App\Models\Coupon::where('code', strtoupper($request->coupon_code))
                    ->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>=', now()->toDateString());
                    })
                    ->first();

                if ($appliedCoupon) {
                    if (!$appliedCoupon->max_usage || $appliedCoupon->used_count < $appliedCoupon->max_usage) {
                        if (!$appliedCoupon->price_min_order_value || $subTotal >= $appliedCoupon->price_min_order_value) {
                            $couponId = $appliedCoupon->id;
                            $discountAmount = $appliedCoupon->type === 'percentage'
                                ? round($subTotal * $appliedCoupon->discount_value / 100)
                                : $appliedCoupon->discount_value;
                        }
                    }
                }
            }

            $finalAmount = max(0, $subTotal - $discountAmount) + $request->shipping_fee;

            // ── 2. Tạo đơn hàng ───────────────────────────────────────────
            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_id' => $customer->id,
                'coupon_id' => $couponId,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'sub_total_amount' => $subTotal,
                'coupon_discount_amount' => $discountAmount,
                'shipping_fee' => $request->shipping_fee,
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
            ]);

            // Tăng số lượt sử dụng coupon
            if ($appliedCoupon && $couponId) {
                $appliedCoupon->increment('used_count');
            }

            // ── 3. Tạo order_details & trừ tồn kho ──────────────────────
            foreach ($orderItems as $oi) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $oi['variant']->id,
                    'quantity' => $oi['quantity'],
                    'unit_price' => $oi['unit_price'],
                    'cost_price' => $oi['cost_price'],
                ]);

                $oi['variant']->decrement('stock_quantity', $oi['quantity']);
            }

            DB::commit();

            // ── 4. Xử lý theo payment_method ─────────────────────────────
            if ($request->payment_method === 'cod') {
                $order->update(['payment_status' => 'unpaid', 'status' => 'confirmed']);

                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công!',
                    'data' => ['order_code' => $order->order_code],
                    'payment_url' => null,
                ]);
            }

            // VNPAY
            $paymentUrl = $this->vnpay->createPaymentUrl($order, $request->ip());

            \Illuminate\Support\Facades\Log::info('[VNPAY] Payment URL created', [
                'order_code' => $order->order_code,
                'final_amount' => $order->final_amount,
                'client_ip' => $request->ip(),
                'payment_url' => $paymentUrl,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được tạo. Vui lòng thanh toán.',
                'data' => ['order_code' => $order->order_code],
                'payment_url' => $paymentUrl,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /client/orders — Lấy danh sách đơn hàng của khách hàng.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();

        $orders = Order::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * GET /client/orders/{code} — Xem chi tiết đơn hàng theo mã code.
     */
    public function show(string $code): JsonResponse
    {
        $customer = Auth::user();

        $order = Order::with([
            'details.productVariant.product',
            'details.productVariant.attributeValues.attribute',
            'details.review',
        ])
            ->where('order_code', $code)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }
}
