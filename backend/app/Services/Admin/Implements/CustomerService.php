<?php 

namespace App\Services\Admin\Implements;

use App\Models\Customer;
use App\Repositories\Admin\Interfaces\CustomerRepositoryInterface;
use App\Services\Admin\Interfaces\CustomerServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CustomerService implements CustomerServiceInterface
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepositoryInterface
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->customerRepositoryInterface->paginate($filters);
    }

    public function create(array $data): Customer
    {
        return $this->customerRepositoryInterface->create($data);
    }

    public function update(Model $model, array $data): Customer
    {
        return $this->customerRepositoryInterface->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->customerRepositoryInterface->delete($model);
    }

    public function getAll()
    {
        return $this->customerRepositoryInterface->getAll();
    }

    public function searchByString(string $keyword)
    {
        if (empty($keyword)) {
            return collect([]);
        }

        return $this->customerRepositoryInterface->searchByString($keyword);
    }
}