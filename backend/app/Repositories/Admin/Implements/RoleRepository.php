<?php
namespace App\Repositories\Admin\Implements;

use App\Models\Role;
use App\Repositories\Admin\Interfaces\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;



class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(
        private readonly Role $model
    ){}
    
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
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

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }
    public function syncPermission (Role $role,  array $permissionIds) : void
    {
        $role->permissions()->sync($permissionIds);
    }

}
