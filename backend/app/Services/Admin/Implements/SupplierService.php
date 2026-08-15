<?php

namespace App\Services\Admin\Implements;

use App\Models\Supplier;
use App\Repositories\Admin\Interfaces\SupplierRepositoryInterface;
use App\Services\Admin\Interfaces\SupplierServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class SupplierService implements SupplierServiceInterface
{
    public function __construct(
        private readonly SupplierRepositoryInterface $repo,
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->repo->paginate($filters);
    }

    public function create(array $data): Supplier
    {
        return $this->repo->create($data);
    }

    public function update(Model $model, array $data): Supplier
    {
        return $this->repo->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }

    public function getStats(): array
    {
        return $this->repo->getStats();
    }
}
