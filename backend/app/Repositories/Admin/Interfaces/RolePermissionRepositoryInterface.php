<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RolePermissionRepositoryInterface
{
    /**
     * Lấy tất cả permissions đang được gán cho một role.
     *
     * @param  Role  $role
     * @return Collection<Permission>
     */
    public function getByRole(Role $role): Collection;

    /**
     * Gán một permission vào role (nếu chưa tồn tại thì mới thêm).
     *
     * @param  Role        $role
     * @param  Permission  $permission
     * @return void
     */
    public function add(Role $role, Permission $permission): void;

    /**
     * Gỡ một permission khỏi role.
     *
     * @param  Role        $role
     * @param  Permission  $permission
     * @return void
     */
    public function remove(Role $role, Permission $permission): void;

    /**
     * Đồng bộ lại toàn bộ permissions cho role (xóa cũ, gán mới).
     * Dùng khi submit form phân quyền.
     *
     * @param  Role          $role
     * @param  array<int>    $permissionIds
     * @return void
     */
    public function sync(Role $role, array $permissionIds): void;
}
