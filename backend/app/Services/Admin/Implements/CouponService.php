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
}