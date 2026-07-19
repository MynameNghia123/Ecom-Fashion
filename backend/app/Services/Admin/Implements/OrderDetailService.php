<?php

namespace App\Services\Admin\Implements;

use App\Models\OrderDetail;
use App\Repositories\Admin\Interfaces\OrderDetailRepositoryInterface;
use App\Services\Admin\Interfaces\OrderDetailServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderDetailService implements OrderDetailServiceInterface
{
    public function __construct(
        private readonly OrderDetailRepositoryInterface $orderDetailRepository
    ) {}

    public function getByOrderId(int $orderId): Collection
    {
        return $this->orderDetailRepository->getByOrderId($orderId);
    }

    public function findById(int $id): ?OrderDetail
    {
        return $this->orderDetailRepository->findById($id);
    }

    public function create(array $data): OrderDetail
    {
        return $this->orderDetailRepository->create($data);
    }

    public function createMany(array $detailsData): bool
    {
        return $this->orderDetailRepository->createMany($detailsData);
    }

    public function update(Model $model, array $data): OrderDetail
    {
        return $this->orderDetailRepository->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->orderDetailRepository->delete($model);
    }
}
