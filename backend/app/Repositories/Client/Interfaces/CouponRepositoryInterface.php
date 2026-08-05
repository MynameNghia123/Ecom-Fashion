<?php
namespace App\Repositories\Client\Interfaces;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

interface CouponRepositoryInterface
{
    public function getActiveCoupons(): Collection;
    public function findActiveByCode(string $code): ?Coupon;
}
