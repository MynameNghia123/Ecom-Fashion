<?php
namespace App\Services\Admin\Implements;

use App\Models\Staff;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use App\Repositories\Admin\Interfaces\StaffRepoInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Services\Admin\Interfaces\StaffRoleServiceInterface;
use App\Services\Admin\Interfaces\StaffPermissionServiceInterface;

class StaffService implements StaffServiceInterface
{
    public function __construct(
        private readonly StaffRepoInterface $repo,
        private readonly StaffRoleServiceInterface $staffRoleService,
        private readonly StaffPermissionServiceInterface $staffPermissionService
    ){}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->repo->paginate($filters);
    }

    public function create(array $data): Staff
    {
        $roleIds = $data['role_ids'] ?? [];
        $permissionIds = $data['permission_ids'] ?? [];
        unset($data['role_ids'], $data['permission_ids']);

        $staff = $this->repo->create($data);

        $this->staffRoleService->add($staff->id, $roleIds);
        $this->staffPermissionService->add($staff->id, $permissionIds);

        return $staff;
    }

    public function update(Model $model, array $data): Staff
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $hasRoles = array_key_exists('role_ids', $data);
        $roleIds = $data['role_ids'] ?? [];
        
        $hasPermissions = array_key_exists('permission_ids', $data);
        $permissionIds = $data['permission_ids'] ?? [];
        
        unset($data['role_ids'], $data['permission_ids']);

        $staff = $this->repo->update($model, $data);

        if ($hasRoles) {
            $this->staffRoleService->remove($staff->id);
            $this->staffRoleService->add($staff->id, $roleIds);
        }

        if ($hasPermissions) {
            $this->staffPermissionService->remove($staff->id);
            $this->staffPermissionService->add($staff->id, $permissionIds);
        }

        return $staff;
    }

    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }
}