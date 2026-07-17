<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Permission;
use App\Repositories\Admin\Interfaces\PermissionRepositoryInterface;

class PermissionRepo implements PermissionRepositoryInterface
{
    public function __construct(
        private readonly Permission $model,
    ) {}

    /**
     * Lấy toàn bộ permissions, sắp xếp theo module rồi action,
     * sau đó group theo module để FE dễ dùng.
     *
     * Kết quả dạng:
     * [
     *   'products' => [
     *       ['id' => 1, 'module' => 'products', 'action' => 'create', ...],
     *       ['id' => 2, 'module' => 'products', 'action' => 'delete', ...],
     *   ],
     *   'orders' => [...],
     * ]
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->orderBy('module')->orderBy('action')->get();
    }

    public function getById(int $id): ?Permission
    {
        return $this->model->find($id);
    }

    public function getAllGroupedByModule(): array
    {
        return $this->model
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->groupBy('module')
            ->map(fn ($perms) => $perms->values()->toArray())
            ->toArray();
    }
}