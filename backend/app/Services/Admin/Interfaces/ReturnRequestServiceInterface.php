<?php

namespace App\Services\Admin\Interfaces;

use App\Models\ReturnRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReturnRequestServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;

    public function getStats(): array;

    public function getDetail(int $id): ?ReturnRequest;

    public function createReturnRequest(array $data): ReturnRequest;

    public function updateStatus(ReturnRequest $model, array $data): ReturnRequest;
}
