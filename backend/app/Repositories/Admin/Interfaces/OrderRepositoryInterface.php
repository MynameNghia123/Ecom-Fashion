<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function getStats(): array;

    public function findByIdWithRelations(int $id): ?Order;
}
