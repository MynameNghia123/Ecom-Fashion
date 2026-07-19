<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?Order;
    public function findByCode(string $code): ?Order;
    public function create(array $data): Order;
    public function update(Model $model, array $data): Order;
    public function delete(Model $model): void;
}
