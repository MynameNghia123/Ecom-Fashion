<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\GoodReceipt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface GoodReceiptRepoInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function findById(int $id): ?GoodReceipt;

    public function create(array $data): GoodReceipt;

    public function update(Model $model, array $data): GoodReceipt;

    public function delete(Model $model): void;

    public function getStats(): array;
}
