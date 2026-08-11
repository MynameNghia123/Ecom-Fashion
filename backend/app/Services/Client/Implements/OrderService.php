<?php
namespace App\Services\Client\Implements;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Repositories\Client\Interfaces\OrderRepositoryInterface;
use App\Services\Client\Interfaces\OrderServiceInterface;
use App\Services\SePayService;
use App\Services\VNPayService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repo,
        private readonly VNPayService $vnpay,
        private readonly SePayService $sepay
    ) {}

    public function getCustomerOrders(int $customerId): Collection
    {
        return $this->repo->getCustomerOrders($customerId);
    }

    public function getCustomerOrderDetails(int $customerId, string $orderCode): ?Order
    {
        return $this->repo->getCustomerOrderByCode($customerId, $orderCode);
    }

    public function placeOrder(int $customerId, array $data, string $clientIp): array
    {
        try {
            DB::beginTransaction();

            // 1. Calculate values
            $subTotal = 0;
            $orderItems = [];

            foreach ($data['items'] as $cartItem) {
                $variant = ProductVariant::findOrFail($cartItem['product_variant_id']);

                if ($variant->stock_quantity < $cartItem['quantity']) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Sản phẩm '{$variant->sku}' không đủ số lượng tồn kho.",
                    ];
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

            // Coupon
            $couponId = null;
            $discountAmount = 0;
            $appliedCoupon = null;

            if (!empty($data['coupon_code'])) {
                $appliedCoupon = Coupon::where('code', strtoupper($data['coupon_code']))
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

            $finalAmount = max(0, $subTotal - $discountAmount) + $data['shipping_fee'];

            // 2. Create Order
            $order = $this->repo->createOrder([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_id' => $customerId,
                'coupon_id' => $couponId,
                'shipping_name' => $data['shipping_name'],
                'shipping_phone' => $data['shipping_phone'],
                'shipping_address' => $data['shipping_address'],
                'sub_total_amount' => $subTotal,
                'coupon_discount_amount' => $discountAmount,
                'shipping_fee' => $data['shipping_fee'],
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
            ]);

            // Increment coupon usage
            if ($appliedCoupon && $couponId) {
                $appliedCoupon->increment('used_count');
            }

            // 3. Create Order details & reduce stock
            foreach ($orderItems as $oi) {
                $this->repo->createOrderDetail([
                    'order_id' => $order->id,
                    'product_variant_id' => $oi['variant']->id,
                    'quantity' => $oi['quantity'],
                    'unit_price' => $oi['unit_price'],
                    'cost_price' => $oi['cost_price'],
                ]);

                $oi['variant']->decrement('stock_quantity', $oi['quantity']);
            }

            DB::commit();

            // 4. Process payment
            if ($data['payment_method'] === 'cod') {
                $order->update(['payment_status' => 'unpaid', 'status' => 'confirmed']);

                return [
                    'success' => true,
                    'message' => 'Đặt hàng thành công!',
                    'data'    => ['order_code' => $order->order_code],
                    'payment_url' => null,
                ];
            }

            // SEPAY – chuyển khoản ngân hàng
            if ($data['payment_method'] === 'sepay') {
                $paymentInfo = $this->sepay->createPaymentInfo($order);

                Log::info('[SEPAY] Payment info created', [
                    'order_code' => $order->order_code,
                    'final_amount' => $order->final_amount,
                ]);

                return [
                    'success'      => true,
                    'message'      => 'Đơn hàng đã được tạo. Vui lòng thanh toán qua chuyển khoản.',
                    'data'         => ['order_code' => $order->order_code],
                    'payment_url'  => null,
                    'payment_info' => $paymentInfo,
                ];
            }

            // VNPAY
            $paymentUrl = $this->vnpay->createPaymentUrl($order, $clientIp);

            Log::info('[VNPAY] Payment URL created', [
                'order_code' => $order->order_code,
                'final_amount' => $order->final_amount,
                'client_ip' => $clientIp,
                'payment_url' => $paymentUrl,
            ]);

            return [
                'success' => true,
                'message' => 'Đơn hàng đã được tạo. Vui lòng thanh toán.',
                'data' => ['order_code' => $order->order_code],
                'payment_url' => $paymentUrl,
            ];

        } catch (Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ];
        }
    }
}
