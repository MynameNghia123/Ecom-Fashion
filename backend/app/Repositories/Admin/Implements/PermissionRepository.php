<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Permission;
use App\Repositories\Admin\Interfaces\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class PermissionRepository
 * Truy cập bảng permissions(id, module, action) — composite unique(module, action).
 */
class PermissionRepository implements PermissionRepositoryInterface
{
    public function __construct(
        private readonly Permission $model
    ) {}

    /**
     * Lấy danh sách permission có phân trang.
     * Hỗ trợ tìm kiếm theo module hoặc action.
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (! empty($filters['search'])) {
            $keyword = $filters['search'];
            $query->where(function ($q) use ($keyword) {
                $q->where('module', 'like', "%{$keyword}%")
                    ->orWhere('action', 'like', "%{$keyword}%");
            });
        }

        if (! empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        return $query->orderBy('module')->orderBy('action')
            ->paginate($filters['per_page'] ?? 20);
    }

    /**
     * Lấy toàn bộ permissions, nhóm theo module để dễ render checkbox trên UI.
     */
    public function getAll(): Collection
    {
        return $this->model->orderBy('module')->orderBy('action')->get();
    }
}
