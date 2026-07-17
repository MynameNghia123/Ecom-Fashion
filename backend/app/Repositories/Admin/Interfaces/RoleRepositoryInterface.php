<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function getAll(): \Illuminate\Database\Eloquent\Collection;
    public function findById(int $id): ?Role;
    public function create(array $data): Role;
    public function update(Model $model, array $data): Role;
    public function delete(Model $model): void;
}
