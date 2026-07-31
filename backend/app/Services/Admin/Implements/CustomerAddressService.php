<?php

namespace App\Services\Admin\Implements;

use App\Models\CustomerAddress;
use App\Repositories\Admin\Interfaces\CustomerAddressRepositoryInterface;
use App\Services\Admin\Interfaces\CustomerAddressServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CustomerAddressService implements CustomerAddressServiceInterface
{
    public function __construct(
        private readonly CustomerAddressRepositoryInterface $customerAddressRepositoryInterface
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->customerAddressRepositoryInterface->paginate($filters);
    }

    public function create(array $data): CustomerAddress
    {
        return $this->customerAddressRepositoryInterface->create($data);
    }

    public function update(Model $model, array $data): CustomerAddress
    {
        return $this->customerAddressRepositoryInterface->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->customerAddressRepositoryInterface->delete($model);
    }

    public function getAll()
    {
        return $this->customerAddressRepositoryInterface->getAll();
    }

    public function findById(int $id): ?CustomerAddress
    {
        return $this->customerAddressRepositoryInterface->findById($id);
    }
}
