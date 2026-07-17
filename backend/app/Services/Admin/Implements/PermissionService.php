<?php

namespace App\Services\Admin\Implements;

use App\Services\Admin\Interfaces\PermissionServiceInterface;
use App\Repositories\Admin\Interfaces\PermissionRepositoryInterface;

class PermissionService implements PermissionServiceInterface
{
    public function __construct(
        private readonly PermissionRepositoryInterface $repo,
    ) {}

    /**
     * Lấy toàn bộ permissions rồi group theo module.
     * Kết quả dạng: ['products' => [...], 'orders' => [...], ...]
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getAll();
    }

    public function getById(int $id): ?\App\Models\Permission
    {
        return $this->repo->getById($id);
    }

    public function getAllGroupedByModule(): array
    {
        return $this->repo->getAllGroupedByModule();
    }
}