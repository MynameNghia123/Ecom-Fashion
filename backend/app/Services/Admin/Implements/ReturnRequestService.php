<?php

namespace App\Services\Admin\Implements;

use App\Models\ReturnRequest;
use App\Repositories\Admin\Interfaces\ReturnRequestRepositoryInterface;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ReturnRequestService implements ReturnRequestServiceInterface
{
    public function __construct(
        private readonly ReturnRequestRepositoryInterface $returnRequestRepository
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->returnRequestRepository->paginate($filters);
    }

    public function findById(int $id): ?ReturnRequest
    {
        return $this->returnRequestRepository->findById($id);
    }

    public function create(array $data): ReturnRequest
    {
        return $this->returnRequestRepository->create($data);
    }

    public function update(Model $model, array $data): ReturnRequest
    {
        return $this->returnRequestRepository->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->returnRequestRepository->delete($model);
    }
}
