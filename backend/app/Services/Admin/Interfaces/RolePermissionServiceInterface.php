<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RolePermissionServiceInterface
{
    /**
     * Lấy tất cả permissions đang được gán cho một role.
     *
     * @param  Role  $role
     * @return Collection<Permission>
     */
    public function getByRole(Role $role): Collection;

    /**
     * Gán một permission vào role.
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
     * Đồng bộ lại toàn bộ permissions cho role theo danh sách ID mới.
     * Thường được gọi khi người dùng submit form phân quyền Role.
     *
     * @param  Role        $role
     * @param  array<int>  $permissionIds
     * @return void
     */
    public function sync(Role $role, array $permissionIds): void;
}
