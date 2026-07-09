<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface PermissionRepositoryInterface
 * Contract truy cập dữ liệu cho thực thể Permission (Quyền hạn).
 * Schema: permissions(id, module, action) — composite unique(module, action)
 */
interface PermissionRepositoryInterface
{
    /**
     * Lấy danh sách permission có phân trang, hỗ trợ tìm kiếm theo module/action.
     */
    public function paginate(array $filters): LengthAwarePaginator;

    /**
     * Lấy toàn bộ permissions (không phân trang), dùng cho form gán quyền cho role.
     */
    public function getAll(): Collection;
}
