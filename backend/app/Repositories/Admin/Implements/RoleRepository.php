<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Role;
use App\Repositories\Admin\Interfaces\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class RoleRepository
 * Truy cập bảng roles(id, name, description, created_at, updated_at).
 * Bảng trung gian: role_permissions(role_id, permission_id)
 *                  staff_roles(staff_id, role_id)  ← đúng theo schema
 */
class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(
        private readonly Role $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('permissions');

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    public function findById(int $id): ?Role
    {
        return $this->model->with('permissions')->find($id);
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
        // Detach permissions và staff trước khi xóa (nếu không có cascade)
        $model->permissions()->detach();
        $model->staff()->detach();
        $model->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->with('permissions')->orderBy('id', 'desc')->get();
    }

    /**
     * Đồng bộ permissions vào bảng trung gian role_permissions.
     * sync() = xóa các record cũ không có trong mảng mới + thêm mới.
     */
    public function syncPermissions(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);
    }
}
