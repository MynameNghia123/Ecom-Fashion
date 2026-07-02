<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Staff;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface StaffRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?Staff;
    public function create(array $data): Staff;
    public function update(Model $model, array $data): Model;
    public function delete(Model $model): void;
    public function syncRoles(Staff $staff, array $roleIds): void;
    public function syncPermissions(Staff $staff, array $permissionIds): void;
}