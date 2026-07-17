<?php
namespace App\Services\Admin\Implements;

use App\Repositories\Admin\Interfaces\StaffRoleRepoInterface;
use App\Services\Admin\Interfaces\StaffRoleServiceInterface;

class StaffRoleService implements StaffRoleServiceInterface
{
    public function __construct(
        private readonly StaffRoleRepoInterface $staffRoleRepo
    ) {}

    public function getRoleIdsByStaffId(int $staffId): array
    {
        return $this->staffRoleRepo->getRoleIdsByStaffId($staffId);
    }

    public function add(int $staffId, array $roleIds): void
    {
        if (empty($roleIds)) return;
        $this->staffRoleRepo->add($staffId, $roleIds);
    }

    public function remove(int $staffId, array $roleIds = []): void
    {
        $this->staffRoleRepo->remove($staffId, $roleIds);
    }
}
