<?php
namespace App\Services\Admin\Implements;

use App\Models\Staff;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use App\Repositories\Admin\Interfaces\StaffRepoInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StaffService implements StaffServiceInterface
{
    public function __construct(
        private readonly StaffRepoInterface $repo,
    ){}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->repo->paginate($filters);
    }

    public function create(array $data): Staff
    {
        return $this->repo->create($data);
    }

    public function update(Model $model, array $data): Staff
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }
        return $this->repo->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }
}