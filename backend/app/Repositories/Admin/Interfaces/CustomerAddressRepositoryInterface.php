<?php

namespace App\Repositories\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerAddress;

interface CustomerAddressRepositoryInterface extends BaseRepositoryInterface
{

    public function paginate(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?CustomerAddress;
    public function create(array $data): CustomerAddress;
    public function update(Model $model, array $data): CustomerAddress;
    public function delete(Model $model): void;
    public function getAll();
}
