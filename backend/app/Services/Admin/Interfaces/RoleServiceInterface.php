<?php 
namespace App\Services\Admin\Interfaces;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RoleServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function getAll(): \Illuminate\Database\Eloquent\Collection;
    public function create(array $data): Role;
    public function update(Model $model, array $data): Role;
    public function delete(Model $model): void;
}