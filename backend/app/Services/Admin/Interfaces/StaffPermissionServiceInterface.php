<?php
namespace App\Services\Admin\Interfaces;

interface StaffPermissionServiceInterface
{
    public function getPermissionIdsByStaffId(int $staffId): array;
    public function add(int $staffId, array $permissionIds): void;
    public function remove(int $staffId, array $permissionIds = []): void;
}
