<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermissions;
use App\Repositories\Admin\Interfaces\RolePermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    public function __construct(
        private readonly RolePermissions $model,
    ) {}

    /**
     * Lấy tất cả permissions đang được gán cho role.
     */
    public function getByRole(Role $role): Collection
    {
        return Permission::whereIn(
            'id',
            $this->model->where('role_id', $role->id)->pluck('permission_id')
        )
        ->orderBy('module')
        ->orderBy('action')
        ->get();
    }

    /**
     * Gán một permission vào role (idempotent — không thêm trùng).
     */
    public function add(Role $role, Permission $permission): void
    {
        $this->model->firstOrCreate([
            'role_id'       => $role->id,
            'permission_id' => $permission->id,
        ]);
    }

    /**
     * Gỡ một permission khỏi role.
     */
    public function remove(Role $role, Permission $permission): void
    {
        $this->model
            ->where('role_id', $role->id)
            ->where('permission_id', $permission->id)
            ->delete();
    }

    /**
     * Đồng bộ lại toàn bộ permissions cho role.
     * Xóa tất cả permissions hiện có của role rồi gán lại theo danh sách mới.
     */
    public function sync(Role $role, array $permissionIds): void
    {
        // Xóa tất cả permissions cũ của role
        $this->model->where('role_id', $role->id)->delete();

        // Gán permissions mới
        $rows = array_map(
            fn (int $id) => ['role_id' => $role->id, 'permission_id' => $id],
            array_unique($permissionIds)
        );

        if (!empty($rows)) {
            $this->model->insert($rows);
        }
    }
}
