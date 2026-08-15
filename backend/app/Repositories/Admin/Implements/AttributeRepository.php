<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Attribute;
use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class AttributeRepository implements AttributeRepositoryInterface
{
    public function __construct(
        private readonly Attribute $model
    ) {}

    /**
     * Truy vấn danh sách có filter + phân trang.
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (! empty($filters['search'])) {
            $query->where('name', 'like', $filters['search'].'%');
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Tìm theo ID.
     */
    public function findById(int $id): ?Attribute
    {
        return $this->model->find($id);
    }

    /**
     * INSERT bản ghi mới.
     */
    public function create(array $data): Attribute
    {
        return $this->model->create($data);
    }

    /**
     * UPDATE bản ghi, trả về dữ liệu mới nhất từ DB.
     */
    public function update(Model $model, array $data): Attribute
    {
        $model->update($data);

        return $model->fresh();
    }

    /**
     * DELETE bản ghi.
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }
}
