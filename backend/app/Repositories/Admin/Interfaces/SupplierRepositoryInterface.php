<?php
namespace App\Repositories\Admin\Interfaces;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface SupplierRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?Supplier;
    public function create(array $data): Supplier;
    public function update(Model $model, array $data): Supplier;
    public function delete(Model $model): void;
    public function getStats(): array;
}