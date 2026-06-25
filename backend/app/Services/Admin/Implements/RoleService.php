<?php

namespace App\Services\Admin\Implements;

use App\Models\Role;
use App\Repositories\Admin\Interfaces\RoleRepositoryInterface;
use App\Services\Admin\Interfaces\RoleServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class RoleService
 * Xử lý logic nghiệp vụ cho thực thể Vai trò (Role).
 * Schema: roles(id, name, description, created_at, updated_at)
 * Bảng trung gian: role_permissions(role_id, permission_id)
 *                  staff_roles(staff_id, role_id)
 */
class RoleService implements RoleServiceInterface
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepo
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->roleRepo->paginate($filters);
    }

    public function getAll(): Collection
    {
        return $this->roleRepo->getAll();
    }

    /**
     * Tạo mới role và đồng bộ permission_ids vào role_permissions.
     */
    public function create(array $data): Role
    {
        DB::beginTransaction();
        try {
            $role = $this->roleRepo->create($data);

            $permissionIds = $data['permission_ids'] ?? [];
            if (!empty($permissionIds)) {
                // syncPermissions — đúng tên method trong RoleRepository
                $this->roleRepo->syncPermissions($role, $permissionIds);
            }

            DB::commit();
            // Load lại với eager loading permissions để trả về đầy đủ
            return $role->load('permissions');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật thông tin role và làm mới danh sách quyền hạn.
     */
    public function update(Model $model, array $data): Role
    {
        DB::beginTransaction();
        try {
            $updatedRole = $this->roleRepo->update($model, $data);

            if (isset($data['permission_ids'])) {
                $this->roleRepo->syncPermissions($updatedRole, $data['permission_ids']);
            }

            DB::commit();
            return $updatedRole->load('permissions');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xóa role. Laravel tự xóa các bản ghi trong role_permissions và staff_roles
     * nếu đã cấu hình onDelete cascade trong migration.
     */
    public function delete(Model $model): void
    {
        $this->roleRepo->delete($model);
    }

    /**
     * Đồng bộ riêng danh sách quyền cho role (endpoint POST /roles/{role}/sync-permissions).
     */
    public function syncPermissions(Role $role, array $data): Role
    {
        $permissionIds = $data['permission_ids'] ?? [];
        $this->roleRepo->syncPermissions($role, $permissionIds);
        return $role->fresh()->load('permissions');
    }
}