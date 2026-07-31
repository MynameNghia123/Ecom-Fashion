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
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->couponRepositoryInterface->paginate($filters);
    }

    public function create(array $data): Coupon
    {
        return $this->couponRepositoryInterface->create($data);
    }

    public function update(Model $model, array $data): Coupon
    {
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

    public function findById(int $id): ?Coupon
    {
        return $this->couponRepositoryInterface->findById($id);
    }

    public function checkValidCoupon(array $data): array
    {
        \Illuminate\Support\Facades\Validator::make($data, [
            'code' => 'required|string',
            'order_total' => 'required|numeric|min:0'
        ])->validate();

        $code = $data['code'];
        $orderTotal = (float) $data['order_total'];

        $coupon = $this->couponRepositoryInterface->findByCode($code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại.'];
        }

        if (!$coupon->is_active) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã bị vô hiệu hóa.'];
        }

        if ($coupon->expiry_date && \Carbon\Carbon::parse($coupon->expiry_date)->startOfDay()->isPast()) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn.'];
        }

        if ($coupon->max_usage > 0 && $coupon->used_count >= $coupon->max_usage) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        if ($coupon->price_min_order_value > 0 && $orderTotal < $coupon->price_min_order_value) {
            return ['valid' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để sử dụng mã này.'];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }
}