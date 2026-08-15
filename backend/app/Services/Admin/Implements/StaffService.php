<?php

namespace App\Services\Admin\Implements;

use App\Models\Staff;
use App\Repositories\Admin\Interfaces\StaffRepositoryInterface;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class StaffService
 * Xử lý logic nghiệp vụ cho thực thể Nhân viên (Staff).
 * Schema: staff(id, full_name, email, password, phone_number, avatar,
 *               is_active, last_login_at, created_at, updated_at, deleted_at)
 * Bảng trung gian: staff_roles(staff_id, role_id)
 *                  staff_permissions(staff_id, permission_id)
 */
class StaffService implements StaffServiceInterface
{
    public function __construct(
        private readonly StaffRepositoryInterface $staffRepo
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->staffRepo->paginate($filters);
    }

    /**
     * Tạo mới staff và đồng bộ role_ids + permission_ids.
     */
    public function create(array $data): Staff
    {
        DB::beginTransaction();
        try {
            $roleIds = $data['role_ids'] ?? [];
            $permissionIds = $data['permission_ids'] ?? null;
            unset($data['role_ids'], $data['permission_ids']);

            // Nếu frontend không gửi permission_ids nhưng có gửi role_ids, lấy permission từ roles
            if ($permissionIds === null && ! empty($roleIds)) {
                $permissionIds = $this->staffRepo->getPermissionsByRoles($roleIds);
            }

            $staff = $this->staffRepo->create($data);

            if (! empty($roleIds)) {
                $this->staffRepo->syncRoles($staff, $roleIds);
            }

            if (! empty($permissionIds)) {
                $this->staffRepo->syncPermissions($staff, $permissionIds);
            }

            DB::commit();

            return $staff->load(['roles', 'permissions']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật thông tin staff và làm mới danh sách roles + permissions.
     */
    public function update(Model $model, array $data): Staff
    {
        DB::beginTransaction();
        try {
            $roleIds = $data['role_ids'] ?? null;
            $permissionIds = $data['permission_ids'] ?? null;
            unset($data['role_ids'], $data['permission_ids']);

            // Tự động gán permissions từ roles nếu frontend không truyền permissions
            if ($permissionIds === null && $roleIds !== null) {
                if (! empty($roleIds)) {
                    $permissionIds = $this->staffRepo->getPermissionsByRoles($roleIds);
                } else {
                    $permissionIds = []; // Nếu xóa hết role, cũng xóa luôn permissions
                }
            }

            $updatedStaff = $this->staffRepo->update($model, $data);

            if ($roleIds !== null) {
                $this->staffRepo->syncRoles($updatedStaff, $roleIds);
            }

            if ($permissionIds !== null) {
                $this->staffRepo->syncPermissions($updatedStaff, $permissionIds);
            }

            DB::commit();

            return $updatedStaff->load(['roles', 'permissions']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xóa staff. Repository sẽ detach roles + permissions trước khi xóa.
     */
    public function delete(Model $model): void
    {
        $this->staffRepo->delete($model);
    }
}
