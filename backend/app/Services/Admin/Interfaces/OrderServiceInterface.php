<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function getStats(): array;
    public function getDetail(int $id): ?Order;
}
