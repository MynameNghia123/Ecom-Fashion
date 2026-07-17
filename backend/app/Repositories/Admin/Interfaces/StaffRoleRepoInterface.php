<?php
namespace App\Repositories\Admin\Interfaces;

interface StaffRoleRepoInterface
{
    public function getRoleIdsByStaffId(int $staffId): array;
    public function add(int $staffId, array $roleIds): void;
    public function remove(int $staffId, array $roleIds = []): void;
}
