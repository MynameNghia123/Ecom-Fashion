<?php

namespace App\Repositories\Admin\Interfaces;

interface PermissionRepositoryInterface
{
    /** Lấy toàn bộ permissions dạng flat (Collection). */
    public function getAll(): \Illuminate\Database\Eloquent\Collection;

    /** Tìm permission theo ID, trả về null nếu không tồn tại. */
    public function getById(int $id): ?\App\Models\Permission;

    /**
     * Lấy toàn bộ permissions, nhóm theo module.
     * @return array<string, array>
     */
    public function getAllGroupedByModule(): array;
}
