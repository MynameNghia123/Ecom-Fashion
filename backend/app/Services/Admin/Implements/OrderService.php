<?php

namespace App\Services\Admin\Implements;

use App\Models\Order;
use App\Repositories\Admin\Interfaces\OrderRepositoryInterface;
use App\Services\Admin\Interfaces\CouponServiceInterface;
use App\Services\Admin\Interfaces\CustomerAddressServiceInterface;
use App\Services\Admin\Interfaces\OrderDetailServiceInterface;
use App\Services\Admin\Interfaces\OrderServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderDetailServiceInterface $orderDetailService,
        private readonly CouponServiceInterface $couponService,
        private readonly CustomerAddressServiceInterface $customerAddressService
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->orderRepository->paginate($filters);
    }

    public function findById(int $id): ?Order
    {
        return $this->orderRepository->findById($id);
    }

    public function findByCode(string $code): ?Order
    {
        return $this->orderRepository->findByCode($code);
    }

    private function calculateOrderFinancials(array &$data, array $orderDetails): void
    {
        // 1. Tính tổng tiền hàng (sub_total_amount) từ chi tiết các sản phẩm
        $subTotal = array_reduce($orderDetails, function ($total, $item) {
            return $total + ((float) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 1));
        }, 0.0);

        $data['sub_total_amount'] = round($subTotal, 2);

        // 2. Tự tính toán số tiền được giảm giá từ Coupon trong DB qua CouponService (không tin tưởng client)
        $discountAmount = 0.0;
        $couponId = $data['coupon_id'] ?? null;

        if ($couponId) {
            $coupon = $this->couponService->findById((int) $couponId);

            if ($coupon && $coupon->is_active) {
                // Kiểm tra ngày hết hạn của coupon
                $isNotExpired = !$coupon->expiry_date || now()->startOfDay()->lte($coupon->expiry_date);
                // Kiểm tra giá trị đơn hàng tối thiểu áp dụng coupon
                $meetsMinOrder = !$coupon->price_min_order_value || $subTotal >= $coupon->price_min_order_value;

                if ($isNotExpired && $meetsMinOrder) {
                    if ($coupon->type === 'percent') {
                        $discountAmount = $subTotal * ($coupon->discount_value / 100);
                    } elseif ($coupon->type === 'fixed') {
                        $discountAmount = min($subTotal, (float) $coupon->discount_value);
                    }
                }
            }
        }

        $data['coupon_discount_amount'] = round($discountAmount, 2);

        // 3. Phí vận chuyển
        $shippingFee = (float) ($data['shipping_fee'] ?? 0.0);
        $data['shipping_fee'] = round($shippingFee, 2);

        // 4. Tính toán số tiền thanh toán thực tế cuối cùng (final_amount)
        $data['final_amount'] = max(0, round($data['sub_total_amount'] - $data['coupon_discount_amount'] + $data['shipping_fee'], 2));
    }

    /**
     * Tạo mới đơn hàng kèm danh sách chi tiết đơn hàng trong DB Transaction.
     */
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $orderDetails = $data['order_details'] ?? [];
            unset($data['order_details']);

            if (empty($data['order_code'])) {
                $data['order_code'] = strtoupper(\Illuminate\Support\Str::random(8));
            }

            $this->processCustomerAddress($data);

            // Tự động tính toán lại tài chính đơn hàng phía server
            $this->calculateOrderFinancials($data, $orderDetails);

            // 1. Tạo đơn hàng (Parent Record)
            $createdOrder = $this->orderRepository->create($data);

            // 2. Lưu danh sách chi tiết đơn hàng (OrderDetails)
            if (!empty($orderDetails)) {
                $detailsData = array_map(function ($item) use ($createdOrder) {
                    return [
                        'order_id'           => $createdOrder->id,
                        'product_variant_id' => $item['product_variant_id'],
                        'quantity'           => $item['quantity'],
                        'unit_price'         => $item['unit_price'],
                        'cost_price'         => $item['cost_price'] ?? 0,
                        'is_return'          => $item['is_return'] ?? false,
                        'return_quantity'    => $item['return_quantity'] ?? 0,
                        'return_request_id'  => $item['return_request_id'] ?? null,
                    ];
                }, $orderDetails);

                $this->orderDetailService->createMany($detailsData);
            }

            return $createdOrder->load(['customer', 'coupon', 'orderDetails.productVariant.product']);
        });
    }

    /**
     * Cập nhật đơn hàng và đồng bộ danh sách chi tiết đơn hàng trong DB Transaction.
     */
    public function update(Model $model, array $data): Order
    {
        return DB::transaction(function () use ($model, $data) {
            $hasOrderDetails = array_key_exists('order_details', $data);
            $orderDetails = $data['order_details'] ?? [];
            unset($data['order_details']);

            // Nếu đơn hàng không còn ở trạng thái chờ xác nhận hoặc đã thanh toán,
            // thì không cho phép cập nhật thông tin giao hàng từ request (bảo vệ data)
            if ($model->status !== 'pending' || $model->payment_status !== 'unpaid') {
                $shippingChanged = false;
                if (isset($data['shipping_name']) && $data['shipping_name'] !== $model->shipping_name) $shippingChanged = true;
                if (isset($data['shipping_phone']) && $data['shipping_phone'] !== $model->shipping_phone) $shippingChanged = true;
                if (isset($data['shipping_address']) && $data['shipping_address'] !== $model->shipping_address) $shippingChanged = true;

                if ($shippingChanged) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'shipping_name' => 'Không thể cập nhật giao hàng khi đơn hàng đang xử lý hoặc đã thanh toán.',
                        'shipping_phone' => 'Không thể cập nhật giao hàng khi đơn hàng đang xử lý hoặc đã thanh toán.',
                        'shipping_address' => 'Không thể cập nhật giao hàng khi đơn hàng đang xử lý hoặc đã thanh toán.',
                    ]);
                }

                unset($data['shipping_name']);
                unset($data['shipping_phone']);
                unset($data['shipping_address']);
                unset($data['customer_address']);
            }

            // Lấy danh sách chi tiết đơn hàng cũ nếu client không truyền lại order_details
            if (!$hasOrderDetails) {
                $existingDetails = $model->orderDetails;
                $orderDetails = $existingDetails->map(function ($item) {
                    return [
                        'unit_price' => $item->unit_price,
                        'quantity'   => $item->quantity,
                    ];
                })->toArray();
            }

            if (!isset($data['coupon_id'])) {
                $data['coupon_id'] = $model->coupon_id;
            }

            if (!isset($data['shipping_fee'])) {
                $data['shipping_fee'] = $model->shipping_fee;
            }

            $this->processCustomerAddress($data);

            // Tự động tính toán lại tài chính đơn hàng phía server
            $this->calculateOrderFinancials($data, $orderDetails);

            // 1. Cập nhật đơn hàng (Parent Record)
            $updatedOrder = $this->orderRepository->update($model, $data);

            // 2. Đồng bộ danh sách chi tiết đơn hàng nếu order_details được cung cấp trong payload
            if ($hasOrderDetails) {
                // Xóa chi tiết cũ của đơn hàng
                $model->orderDetails()->delete();

                if (!empty($orderDetails)) {
                    $detailsData = array_map(function ($item) use ($model) {
                        return [
                            'order_id'           => $model->id,
                            'product_variant_id' => $item['product_variant_id'],
                            'quantity'           => $item['quantity'],
                            'unit_price'         => $item['unit_price'],
                            'cost_price'         => $item['cost_price'] ?? 0,
                            'is_return'          => $item['is_return'] ?? false,
                            'return_quantity'    => $item['return_quantity'] ?? 0,
                            'return_request_id'  => $item['return_request_id'] ?? null,
                        ];
                    }, $orderDetails);

                    $this->orderDetailService->createMany($detailsData);
                }
            }

            return $updatedOrder->load(['customer', 'coupon', 'orderDetails.productVariant.product']);
        });
    }

    public function delete(Model $model): void
    {
        $this->orderRepository->delete($model);
    }

    private function processCustomerAddress(array &$data): void
    {
        if (isset($data['customer_address']) && is_array($data['customer_address'])) {
            $address = $data['customer_address'];
            
            if (!empty($address['receiver_name'])) {
                $data['shipping_name'] = $address['receiver_name'];
            }
            if (!empty($address['receiver_phone'])) {
                $data['shipping_phone'] = $address['receiver_phone'];
            }
            
            $addressParts = array_filter([
                $address['detail_address'] ?? null,
                $address['ward'] ?? null,
                $address['district'] ?? null,
                $address['province'] ?? null
            ]);
            
            if (!empty($addressParts)) {
                $data['shipping_address'] = implode(', ', $addressParts);
            }

            // Nếu đây là địa chỉ mới (chưa có id) và có customer_id, lưu vào customer_addresses
            $customerId = $address['customer_id'] ?? ($data['customer_id'] ?? null);
            if (empty($address['id']) && $customerId) {
                $this->customerAddressService->create([
                    'customer_id'    => $customerId,
                    'receiver_name'  => $address['receiver_name'] ?? null,
                    'receiver_phone' => $address['receiver_phone'] ?? null,
                    'province'       => $address['province'] ?? null,
                    'district'       => $address['district'] ?? null,
                    'ward'           => $address['ward'] ?? null,
                    'detail_address' => $address['detail_address'] ?? null,
                    'is_default'     => false,
                ]);
            }
            
            unset($data['customer_address']);
        }
    }
}
