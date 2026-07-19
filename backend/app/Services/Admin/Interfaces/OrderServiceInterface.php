<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface OrderServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?Order;
    public function findByCode(string $code): ?Order;
    public function create(array $data): Order;
    public function update(Model $model, array $data): Order;
    public function delete(Model $model): void;
}
