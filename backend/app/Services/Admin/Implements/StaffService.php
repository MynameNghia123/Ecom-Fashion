<?php 

namespace App\Services\Admin\Implements;

use App\Models\Staff;
use App\Repositories\Admin\Interfaces\StaffRepositoryInterface;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class StaffService implements StaffServiceInterface
{
    public function __construct(
        private readonly StaffRepositoryInterface $staffRepository
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->staffRepository->paginate($filters);
    }

    public function create(array $data): Staff
    {
        return $this->staffRepository->create($data);
    }

    public function update(Model $model, array $data): Staff
    {
        return $this->staffRepository->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->staffRepository->delete($model);
    }

    public function getAll()
    {
        return $this->staffRepository->getAll();
    }
}
