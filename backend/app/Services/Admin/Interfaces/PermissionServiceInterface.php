<?php

namespace App\Services\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface PermissionServiceInterface
 * Định nghĩa contract nghiệp vụ cho thực thể Permission.
 */
interface PermissionServiceInterface
{
    /**
     * Lấy danh sách permission phân trang (dùng cho trang quản lý).
     */
    public function getList(array $filters): LengthAwarePaginator;

    /**
     * Lấy toàn bộ permissions không phân trang (dùng cho form assign quyền cho role).
     */
    public function getAll(): Collection;
}
