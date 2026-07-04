<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Blog;
use App\Repositories\Admin\Interfaces\BlogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class BlogRepository implements BlogRepositoryInterface
{
    public function __construct(
        private readonly Blog $model,
    ){}
    
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $statusVal = ($filters['status'] === 'active');
            $query->where('status', $statusVal);
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }
    public function findById(int $id): ?Blog
    {
        return $this->model->find($id);
    }
    public function create(array $data): Blog
    {
        return $this->model->create($data);
    }
    public function update(Model $model, array $data): Blog
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
?>