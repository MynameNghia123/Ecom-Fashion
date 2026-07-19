<?php

namespace App\Services\Admin\Interfaces;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface OrderDetailServiceInterface 
{
    public function getByOrderId(int $orderId): Collection;
    public function findById(int $id): ?OrderDetail;
    public function create(array $data): OrderDetail;
    public function createMany(array $detailsData): bool;
    public function update(Model $model, array $data): OrderDetail;
    public function delete(Model $model): void;
}
