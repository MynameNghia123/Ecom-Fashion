<?php
namespace App\Repositories\Admin\Implements;

use App\Models\Role;
use App\Repositories\Admin\Interfaces\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class RoleRepo implements RoleRepositoryInterface{
    public function __construct(
        private readonly Role $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $keyword = $filters['search'];
            $query->where(function ($q) use ($keyword){
                $q->where('name', 'like', $keyword . '%');
            });
        }

        return $query->with('rolePermissions.permission')->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all();
    }

    public function findById(int $id): ?Role
    {
        return $this->model->find($id);
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Role
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }
}