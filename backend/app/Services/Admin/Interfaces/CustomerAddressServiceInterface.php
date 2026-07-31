<?php

namespace App\Services\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerAddress;

interface CustomerAddressServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function create(array $data): CustomerAddress;
    public function update(Model $model, array $data): CustomerAddress;
    public function delete(Model $model): void;
    public function getAll();
    public function findById(int $id): ?CustomerAddress;
}
