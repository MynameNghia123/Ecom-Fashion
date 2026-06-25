<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface RoleServiceInterface
 * Định nghĩa các hợp đồng nghiệp vụ (Business Logic) cho thực thể Vai trò (Role).
 */
interface RoleServiceInterface
{
    /**
     * Lấy danh sách vai trò có phân trang và bộ lọc.
     */
    public function getList(array $filters): LengthAwarePaginator;

    /**
     * Lấy toàn bộ vai trò không phân trang (dùng cho dropdown, form gán role cho staff).
     */
    public function getAll(): Collection;

    /**
     * Tạo mới vai trò và đồng bộ quyền hạn đi kèm.
     */
    public function create(array $data): Role;

    /**
     * Cập nhật thông tin vai trò và làm mới danh sách quyền hạn.
     */
    public function update(Model $model, array $data): Role;

    /**
     * Xóa vai trò (tự động detach permissions và staff qua cascade).
     */
    public function delete(Model $model): void;

    /**
     * Đồng bộ riêng danh sách quyền cho vai trò (endpoint /sync-permissions).
     */
    public function syncPermissions(Role $role, array $data): Role;
}
