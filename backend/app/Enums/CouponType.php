<?php

namespace App\Enums;

enum CouponType: string
{
    case PERCENT = 'percent';
    case FIXED = 'fixed';

    public function calculateDiscount(float $orderTotal, float $discountValue): float
    {
        return match ($this) {
            self::PERCENT => round($orderTotal * $discountValue / 100),
            self::FIXED => $discountValue,
        };
    }
}
