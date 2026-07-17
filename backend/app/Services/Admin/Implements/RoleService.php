<?php

namespace App\Services\Admin\Implements;

use App\Models\Role;
use App\Repositories\Admin\Interfaces\RoleRepositoryInterface;
use App\Services\Admin\Interfaces\RolePermissionServiceInterface;
use App\Services\Admin\Interfaces\RoleServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class RoleService implements RoleServiceInterface
{
    public function __construct(
        private readonly RoleRepositoryInterface        $repo,
        private readonly RolePermissionServiceInterface $rolePermissionService,
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->repo->paginate($filters);
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getAll();
    }

    /**
     * Tạo Role mới.
     * Nếu $data có key 'permission_ids', tự động gán permissions sau khi tạo.
     */
    public function create(array $data): Role
    {
        $permissionIds = $data['permission_ids'] ?? [];
        unset($data['permission_ids']); // Không truyền vào DB

        $role = $this->repo->create($data);

        if (!empty($permissionIds)) {
            $this->rolePermissionService->sync($role, $permissionIds);
        }

        return $role->load('rolePermissions.permission');
    }

    /**
     * Cập nhật Role.
     * Nếu $data có key 'permission_ids', đồng bộ lại permissions.
     * Nếu key không tồn tại, giữ nguyên permissions cũ.
     */
    public function update(Model $model, array $data): Role
    {
        $hasPermissions = array_key_exists('permission_ids', $data);
        $permissionIds  = $data['permission_ids'] ?? [];
        unset($data['permission_ids']);

        $role = $this->repo->update($model, $data);

        if ($hasPermissions) {
            $this->rolePermissionService->sync($role, $permissionIds);
        }

        return $role->load('rolePermissions.permission');
    }

    /**
     * Xóa Role. Tự động dọn sạch permissions trước khi xóa.
     */
    public function delete(Model $model): void
    {
        $this->rolePermissionService->sync($model, []); // Dọn pivot trước
        $this->repo->delete($model);
    }
}