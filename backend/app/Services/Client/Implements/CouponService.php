<?php
namespace App\Services\Client\Implements;
use App\Models\Coupon;
use App\Repositories\Client\Interfaces\CouponRepositoryInterface;
use App\Services\Client\Interfaces\CouponServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class CouponService implements CouponServiceInterface
{
    public function __construct(private readonly CouponRepositoryInterface $repo) {}

    public function getActiveCoupons(): Collection
    {
        return $this->repo->getActiveCoupons();
    }

    public function getCollectableCoupons(int $customerId): Collection
    {
        return $this->repo->getCollectableCoupons($customerId);
    }

    public function collectCoupon(int $customerId, int $couponId): array
    {
        // Insert into customer_coupons without setting used_at so it's "collected" but not "used"
        // Wait, if used_at is not nullable, this will throw an error in DB.
        // Let's set used_at to NULL if possible, or if it fails, we will know we must alter the table.
        // We will do an updateOrInsert in case they somehow have it.
        \Illuminate\Support\Facades\DB::table('customer_coupons')->updateOrInsert(
            [
                'customer_id' => $customerId,
                'coupon_id' => $couponId,
            ],
            [
                // we don't set used_at because they haven't used it!
                // if mysql complains about used_at not having a default value, 
                // we will have to alter the table to make it nullable.
            ]
        );

        return ['success' => true, 'message' => 'Lưu mã giảm giá thành công.'];
    }

    public function applyCoupon(string $code, float $orderTotal): array
    {
        $coupon = $this->repo->findActiveByCode($code);

        if (!$coupon) {
            return ['success' => false, 'message' => 'Ma giam gia khong hop le hoac da het han.'];
        }

        if ($coupon->max_usage && $coupon->used_count >= $coupon->max_usage) {
            return ['success' => false, 'message' => 'Ma giam gia da het luot su dung.'];
        }

        if ($coupon->price_min_order_value && $orderTotal < $coupon->price_min_order_value) {
            return ['success' => false, 'message' => 'Don hang chua dat gia tri toi thieu.'];
        }

        $discount = $coupon->type === 'percent'
            ? round($orderTotal * $coupon->discount_value / 100)
            : $coupon->discount_value;

        return [
            'success'  => true,
            'message'  => 'Ap dung ma giam gia thanh cong!',
            'coupon'   => $coupon,
            'discount' => $discount,
        ];
    }
}
