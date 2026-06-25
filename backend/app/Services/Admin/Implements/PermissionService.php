<?php

namespace App\Services\Admin\Implements;

use App\Repositories\Admin\Interfaces\PermissionRepositoryInterface;
use App\Services\Admin\Interfaces\PermissionServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class PermissionService
 * Xử lý nghiệp vụ cho thực thể Permission.
 * Permission được seed sẵn theo module + action từ schema:
 *   modules: products, categories, attributes, customers, orders,
 *            coupons, staff, roles, reviews, banners, blogs, settings
 *   actions: view, create, update, delete
 */
class PermissionService implements PermissionServiceInterface
{
    public function __construct(
        private readonly PermissionRepositoryInterface $permissionRepo
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->permissionRepo->paginate($filters);
    }

    public function getAll(): Collection
    {
        return $this->permissionRepo->getAll();
    }
}
