<?php
namespace App\Repositories\Admin\Implements;

use App\Models\StaffRoles;
use App\Repositories\Admin\Interfaces\StaffRoleRepoInterface;

class StaffRoleRepo implements StaffRoleRepoInterface
{
    public function getRoleIdsByStaffId(int $staffId): array
    {
        return StaffRoles::where('staff_id', $staffId)->pluck('role_id')->toArray();
    }

    public function add(int $staffId, array $roleIds): void
    {
        if (empty($roleIds)) return;
        
        $data = array_map(fn($roleId) => [
            'staff_id' => $staffId,
            'role_id'  => $roleId,
            'created_at' => now(),
            'updated_at' => now()
        ], $roleIds);

        StaffRoles::insert($data);
    }

    public function remove(int $staffId, array $roleIds = []): void
    {
        $query = StaffRoles::where('staff_id', $staffId);
        if (!empty($roleIds)) {
            $query->whereIn('role_id', $roleIds);
        }
        $query->delete();
    }
}
