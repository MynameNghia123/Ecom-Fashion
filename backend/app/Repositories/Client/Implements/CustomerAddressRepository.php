<?php

namespace App\Repositories\Client\Implements;

use App\Models\CustomerAddress;
use App\Repositories\Client\Interfaces\CustomerAddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CustomerAddressRepository implements CustomerAddressRepositoryInterface
{
    public function __construct(private readonly CustomerAddress $model) {}

    public function getByCustomerId(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findByIdAndCustomerId(int $id, int $customerId): ?CustomerAddress
    {
        return $this->model->where('id', $id)->where('customer_id', $customerId)->first();
    }

    public function countByCustomerId(int $customerId): int
    {
        return $this->model->where('customer_id', $customerId)->count();
    }

    public function resetDefaultByCustomerId(int $customerId): int
    {
        return $this->model->where('customer_id', $customerId)->update(['is_default' => false]);
    }

    public function getOtherAddress(int $customerId, int $excludeId): ?CustomerAddress
    {
        return $this->model->where('customer_id', $customerId)->where('id', '!=', $excludeId)->first();
    }

    public function getLatestAddress(int $customerId): ?CustomerAddress
    {
        return $this->model->where('customer_id', $customerId)->orderBy('created_at', 'desc')->first();
    }

    public function create(array $data): CustomerAddress
    {
        return $this->model->create($data);
    }

    public function update(CustomerAddress $address, array $data): bool
    {
        return $address->update($data);
    }

    public function delete(CustomerAddress $address): bool
    {
        return $address->delete();
    }
}
