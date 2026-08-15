<?php

namespace App\Repositories\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function getStats(): array;
}
