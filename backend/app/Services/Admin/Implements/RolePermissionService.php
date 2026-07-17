<?php

namespace App\Services\Admin\Implements;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\Admin\Interfaces\RolePermissionRepositoryInterface;
use App\Services\Admin\Interfaces\RolePermissionServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionService implements RolePermissionServiceInterface
{
    public function __construct(
        private readonly RolePermissionRepositoryInterface $repo,
    ) {}

    /**
     * Lấy tất cả permissions được gán cho role.
     */
    public function getByRole(Role $role): Collection
    {
        return $this->repo->getByRole($role);
    }

    /**
     * Gán một permission vào role.
     */
    public function add(Role $role, Permission $permission): void
    {
        $this->repo->add($role, $permission);
    }

    /**
     * Gỡ một permission khỏi role.
     */
    public function remove(Role $role, Permission $permission): void
    {
        $this->repo->remove($role, $permission);
    }

    /**
     * Đồng bộ lại toàn bộ permissions cho role.
     * Validate permissionIds hợp lệ trước khi delegate xuống repo.
     *
     * @param  Role        $role
     * @param  array<int>  $permissionIds  — mảng các permission_id hợp lệ
     */
    public function sync(Role $role, array $permissionIds): void
    {
        $this->repo->sync($role, $permissionIds);
    }
}
