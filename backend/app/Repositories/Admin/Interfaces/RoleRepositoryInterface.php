<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface RoleRepositoryInterface
 * Định nghĩa contract truy cập dữ liệu cho thực thể Role (Vai trò).
 */
interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function findById(int $id): ?Role;

    public function create(array $data): Role;

    public function update(Model $model, array $data): Role;

    public function delete(Model $model): void;

    public function getAll(): Collection;

    /**
     * Đồng bộ (sync) danh sách permission_ids vào bảng trung gian role_permissions.
     */
    public function syncPermissions(Role $role, array $permissionIds): void;
}
