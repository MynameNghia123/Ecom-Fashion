<?php
namespace App\Repositories\Client\Implements;
use App\Models\Coupon;
use App\Repositories\Client\Interfaces\CouponRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CouponRepository implements CouponRepositoryInterface
{
    public function __construct(private readonly Coupon $model) {}

    public function getActiveCoupons(): Collection
    {
        return $this->model->where('is_active', true)
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
    }

    public function findActiveByCode(string $code): ?Coupon
    {
        return $this->model->where('code', strtoupper($code))
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now()->toDateString());
            })
            ->first();
    }

    public function getCollectableCoupons(int $customerId): Collection
    {
        return $this->model->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('max_usage')
                  ->orWhereColumn('used_count', '<', 'max_usage');
            })
            ->whereDoesntHave('orders', function ($q) use ($customerId) {
                $q->where('customer_id', $customerId);
            }) // Note: Orders uses the coupon. But customer_coupons also saves it.
            // Wait, does the coupon model have a customers() relation? Let's check Coupon model.
            ->whereNotIn('id', function($q) use ($customerId) {
                $q->select('coupon_id')->from('customer_coupons')->where('customer_id', $customerId);
            })
            ->orderBy('expiry_date')
            ->get();
    }
}
