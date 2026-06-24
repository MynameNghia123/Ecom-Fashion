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
 * * Xử lý logic nghiệp vụ cho thực thể Vai trò (Role).
 * Đóng vai trò trung gian phối hợp giữa Controller và Repository.
 * * @package App\Services\Admin\Implements
 */
class RoleService implements RoleServiceInterface
{
    /**
     * Tiêm lớp Interface của Repository thông qua Constructor.
     * * @param RoleRepositoryInterface $roleRepo
     */
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepo
    ){}

    /**
     * Ủy quyền (Delegate) cho Repository xử lý lấy danh sách phân trang.
     */
    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->roleRepo->paginate($filters);
    }

    /**
     * Ủy quyền cho Repository lấy toàn bộ danh sách vai trò.
     */
    public function getAll(): Collection
    {
        return $this->roleRepo->getAll();
    }

    /**
     * Xử lý nghiệp vụ tạo mới vai trò.
     * Sử dụng Database Transaction để đảm bảo tính toàn vẹn dữ liệu khi ghi vào nhiều bảng.
     */
    public function create(array $data): Role
    {
        DB::beginTransaction();
        try {
            // 1. Tạo bản ghi vai trò chính trong bảng 'roles'
            $role = $this->roleRepo->create($data);

            // 2. Nếu có mảng ID quyền gửi lên, tiến hành đồng bộ vào bảng trung gian 'role_permissions'
            $permissionIds = $data['permission_ids'] ?? [];
            if (!empty($permissionIds)) {
                $this->roleRepo->syncPermissions($role, $permissionIds);
            }

            DB::commit();
            return $role;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xử lý nghiệp vụ cập nhật thông tin vai trò và làm mới quyền hạn.
     */
    public function update(Model $model, array $data): Role
    {
        DB::beginTransaction();
        try {
            // 1. Cập nhật các thông tin cơ bản (name, description)
            $updatedRole = $this->roleRepo->update($model, $data);

            // 2. Làm mới danh sách quyền hạn nếu phía Front-end có truyền mảng này lên
            if ($updatedRole instanceof Role && isset($data['permission_ids'])) {
                $this->roleRepo->syncPermissions($updatedRole, $data['permission_ids']);
            }

            DB::commit();
            return $updatedRole;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Ủy quyền cho Repository xóa vai trò khỏi hệ thống.
     */
    public function delete(Model $model): void
    {
        $this->roleRepo->delete($model);
    }
}