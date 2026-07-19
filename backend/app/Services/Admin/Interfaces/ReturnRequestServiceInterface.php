<?php

namespace App\Services\Admin\Interfaces;

use App\Models\ReturnRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ReturnRequestServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?ReturnRequest;
    public function create(array $data): ReturnRequest;
    public function update(Model $model, array $data): ReturnRequest;
    public function delete(Model $model): void;
}
