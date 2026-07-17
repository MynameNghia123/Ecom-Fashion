<?php
namespace App\Services\Admin\Interfaces;

interface StaffRoleServiceInterface
{
    public function getRoleIdsByStaffId(int $staffId): array;
    public function add(int $staffId, array $roleIds): void;
    public function remove(int $staffId, array $roleIds = []): void;
}
