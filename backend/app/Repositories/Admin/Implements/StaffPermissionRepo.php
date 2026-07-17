<?php
namespace App\Repositories\Admin\Implements;

use App\Models\StaffPermissions;
use App\Repositories\Admin\Interfaces\StaffPermissionRepoInterface;

class StaffPermissionRepo implements StaffPermissionRepoInterface
{
    public function getPermissionIdsByStaffId(int $staffId): array
    {
        return StaffPermissions::where('staff_id', $staffId)->pluck('permission_id')->toArray();
    }

    public function add(int $staffId, array $permissionIds): void
    {
        if (empty($permissionIds)) return;

        $data = array_map(fn($permissionId) => [
            'staff_id'      => $staffId,
            'permission_id' => $permissionId,
            'created_at'    => now(),
            'updated_at'    => now()
        ], $permissionIds);

        StaffPermissions::insert($data);
    }

    public function remove(int $staffId, array $permissionIds = []): void
    {
        $query = StaffPermissions::where('staff_id', $staffId);
        if (!empty($permissionIds)) {
            $query->whereIn('permission_id', $permissionIds);
        }
        $query->delete();
    }
}
