<?php

namespace App\Services\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;

    public function getStats(): array;

    public function deleteReview(int $id): void;
}
