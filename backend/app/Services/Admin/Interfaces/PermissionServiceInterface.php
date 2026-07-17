<?php

namespace App\Services\Admin\Interfaces;

use Illuminate\Support\Collection;

interface PermissionServiceInterface
{
    /**
     * Lấy toàn bộ permissions dạng flat.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Tìm permission theo ID.
     *
     * @param int $id
     * @return \App\Models\Permission|null
     */
    public function getById(int $id): ?\App\Models\Permission;

    /**
     * Lấy toàn bộ permissions, nhóm theo module.
     * Dùng để FE render bảng phân quyền khi tạo/sửa Role.
     *
     * @return array<string, Collection>  key = module name, value = collection of permissions
     */
    public function getAllGroupedByModule(): array;
}