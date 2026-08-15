<?php

namespace App\Services\Admin\Implements;

use App\Repositories\Admin\Interfaces\ReviewRepositoryInterface;
use App\Services\Admin\Interfaces\ReviewServiceInterface;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ReviewService implements ReviewServiceInterface
{
    public function __construct(private readonly ReviewRepositoryInterface $repository) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->repository->paginate($filters);
    }

    public function getStats(): array
    {
        return $this->repository->getStats();
    }

    public function deleteReview(int $id): void
    {
        $review = $this->repository->findById($id);
        if (! $review) {
            throw new Exception('Không tìm thấy đánh giá này.');
        }
        $this->repository->delete($review);
    }

    public function create(array $data): Model
    {
        throw new Exception('Not implemented');
    }

    public function update(Model $model, array $data): Model
    {
        throw new Exception('Not implemented');
    }

    public function delete(Model $model): void
    {
        $this->repository->delete($model);
    }
}
