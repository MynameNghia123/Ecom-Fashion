<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Supplier;
use App\Repositories\Admin\Interfaces\SupplierRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function __construct(
        private readonly Supplier $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (! empty($filters['search'])) {
            $keyword = $filters['search'];

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', $keyword.'%')
                    ->orWhere('phone', 'like', $keyword.'%')
                    ->orWhere('address', 'like', $keyword.'%')
                    ->orWhere('email', 'like', $keyword.'%');
            });
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 4);
    }

    public function findById(int $id): ?Supplier
    {
        return $this->model->find($id);
    }

    public function create(array $data): Supplier
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Supplier
    {
        $model->update($data);

        return $model->fresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function getStats(): array
    {
        $total = $this->model->count();
        $active = $this->model->where('is_active', true)->count();
        $inactive = $total - $active;

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
        ];
    }
}
