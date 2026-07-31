<?php
namespace App\Repositories\Admin\Implements;

use App\Models\Staff;
use App\Repositories\Admin\Interfaces\StaffRepoInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class StaffRepo implements StaffRepoInterface
{
    public function __construct(
        private readonly Staff $model
    ){}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])){
            $keyword = $filters['search'];

            $query->where(function ($q) use ($keyword){
                $q->where('full_name', 'like', $keyword. '%')
                    ->orWhere('email', 'like', $keyword . '%')
                    ->orWhere('phone_number', 'like', $keyword. '%');
            });
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->with(['StaffRoles', 'StaffPermissions'])->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 4);
    }

    public function findById(int $id): ?Staff
    {
        return $this->model->with(['StaffRoles', 'StaffPermissions'])->find($id);
    }


    public function create(array $data): Staff
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Staff
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function findActiveByEmail(string $email): ?Staff
    {
        return $this->model->where('email', $email)
            ->where('is_active', 1)
            ->first();
    }
}