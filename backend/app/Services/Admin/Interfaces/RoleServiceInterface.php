<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface RoleServiceInterface
 * * Định nghĩa các hợp đồng nghiệp vụ (Business Logic) cho thực thể Vai trò (Role).
 * * @package App\Services\Admin\Interfaces
 */
interface RoleServiceInterface 
{
    /**
     * Lấy danh sách vai trò có phân trang và bộ lọc.
     * * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getList(array $filters): LengthAwarePaginator;

    /**
     * Tạo mới vai trò và đồng bộ quyền hạn đi kèm.
     * * @param array $data
     * @return Role
     */
    public function create(array $data): Role;

    /**
     * Cập nhật thông tin vai trò và làm mới danh sách quyền hạn.
     * * @param Model $model
     * @param array $data
     * @return Role
     */
    public function update(Model $model, array $data): Role;

    /**
     * Xóa vai trò và làm mới danh sách quyền hạn.
     * * @param Model $model
     * @return void
     */
    public function delete(Model $model): void;
}
