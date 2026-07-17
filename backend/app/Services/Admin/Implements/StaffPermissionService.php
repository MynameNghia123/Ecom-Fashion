<?php
namespace App\Services\Admin\Implements;

use App\Repositories\Admin\Interfaces\StaffPermissionRepoInterface;
use App\Services\Admin\Interfaces\StaffPermissionServiceInterface;

class StaffPermissionService implements StaffPermissionServiceInterface
{
    public function __construct(
        private readonly StaffPermissionRepoInterface $staffPermissionRepo
    ) {}

    public function getPermissionIdsByStaffId(int $staffId): array
    {
        return $this->staffPermissionRepo->getPermissionIdsByStaffId($staffId);
    }

    public function add(int $staffId, array $permissionIds): void
    {
        if (empty($permissionIds)) return;
        $this->staffPermissionRepo->add($staffId, $permissionIds);
    }

    public function remove(int $staffId, array $permissionIds = []): void
    {
        $this->staffPermissionRepo->remove($staffId, $permissionIds);
    }
}
