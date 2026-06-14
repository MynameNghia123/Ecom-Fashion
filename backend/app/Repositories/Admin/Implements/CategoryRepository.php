<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Category;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly Category $model
    ){}
    
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', $filters['search'] . '%');
        }

        if (isset($filters['parent_id']) && $filters['parent_id'] !== '') {
            if ($filters['parent_id'] === 'null' || $filters['parent_id'] === 'none' || $filters['parent_id'] === '0') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $filters['parent_id']);
            }
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }
    public function findById(int $id): ?Category
    {
        return $this->model->find($id);
    }
    public function create(array $data): Category
    {
        return $this->model->create($data);
    }
    public function update(Model $model, array $data): Category
    {
        $model->update($data);

        return $model->fresh();
    }   
    public function delete(Model $model): void
    {
        $model->delete();
    }

}
?>