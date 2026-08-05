<?php
namespace App\Services\Client\Interfaces;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

interface CouponServiceInterface
{
    public function getActiveCoupons(): Collection;

    /**
     * @return array{success: bool, message: string, coupon?: Coupon, discount?: float|int}
     */
    public function applyCoupon(string $code, float $orderTotal): array;
}
