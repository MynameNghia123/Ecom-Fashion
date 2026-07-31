<?php

namespace App\Services\Admin\Implements;

use App\Models\Coupon;
use App\Repositories\Admin\Interfaces\CouponRepositoryInterface;
use App\Services\Admin\Interfaces\CouponServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CouponService implements CouponServiceInterface
{
    public function __construct(
        private readonly CouponRepositoryInterface $couponRepositoryInterface
    ) {
    }

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->couponRepositoryInterface->paginate($filters);
    }

    public function create(array $data): Coupon
    {
        if (!isset($data['price_min_order_value']) || $data['price_min_order_value'] === null) {
            $data['price_min_order_value'] = 0;
        }
        if (!isset($data['max_usage']) || $data['max_usage'] === null) {
            $data['max_usage'] = 999999;
        }
        if (!isset($data['expiry_date']) || $data['expiry_date'] === null) {
            $data['expiry_date'] = '2037-12-31 23:59:59';
        }

        return $this->couponRepositoryInterface->create($data);
    }

    public function update(Model $model, array $data): Coupon
    {
        if (array_key_exists('price_min_order_value', $data) && $data['price_min_order_value'] === null) {
            $data['price_min_order_value'] = 0;
        }
        if (array_key_exists('max_usage', $data) && $data['max_usage'] === null) {
            $data['max_usage'] = 999999;
        }
        if (array_key_exists('expiry_date', $data) && $data['expiry_date'] === null) {
            $data['expiry_date'] = '2037-12-31 23:59:59';
        }

        return $this->couponRepositoryInterface->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->couponRepositoryInterface->delete($model);
    }

    public function getAll()
    {
        return $this->couponRepositoryInterface->getAll();
    }
}