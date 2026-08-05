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

        $discount = $coupon->type === 'percentage'
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
