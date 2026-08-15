<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface SupplierServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;

    public function create(array $data): Supplier;

    public function update(Model $model, array $data): Supplier;

    public function delete(Model $model): void;

    public function getStats(): array;
}
