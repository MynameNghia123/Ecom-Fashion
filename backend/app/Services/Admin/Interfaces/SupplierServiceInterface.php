<?php 
namespace App\Services\Admin\Interfaces;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SupplierServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function create(array $data): Supplier;
    public function update(Model $model, array $data): Supplier;
    public function delete(Model $model): void;
}