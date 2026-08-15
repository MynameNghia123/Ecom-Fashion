<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Coupon;
use App\Repositories\Admin\Interfaces\CouponRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CouponRepository implements CouponRepositoryInterface
{
    public function __construct(
        private readonly Coupon $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (! empty($filters['search'])) {
            $query->where('code', 'like', '%'.$filters['search'].'%');
        }

        if (isset($filters['type']) && $filters['type'] !== '') {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    public function findById(int $id): ?Coupon
    {
        return $this->model->find($id);
    }

    public function create(array $data): Coupon
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Coupon
    {
        $model->update($data);

        return $model->fresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }
}
