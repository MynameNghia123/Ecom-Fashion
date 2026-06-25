<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Staff;
use App\Repositories\Admin\Interfaces\StaffRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class StaffRepository implements StaffRepositoryInterface
{
    public function __construct(
        private readonly Staff $model
    ){}
    
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('roles');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('full_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone_number', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_active', (bool) $filters['status']);
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    public function findById(int $id): ?Staff
    {
        return $this->model->with('roles')->find($id);
    }

    public function create(array $data): Staff
    {
        $roleIds = $data['role_ids'] ?? [];
        unset($data['role_ids']);

        $staff = $this->model->create($data);

        if (!empty($roleIds)) {
            $staff->roles()->sync($roleIds);
        }

        return $staff->load('roles');
    }

    public function update(Model $model, array $data): Staff
    {
        $roleIds = $data['role_ids'] ?? null;
        unset($data['role_ids']);

        // Nếu không đổi mật khẩu, loại bỏ trường password tránh ghi đè rỗng
        if (isset($data['password'])) {
            if (empty($data['password'])) {
                unset($data['password']);
            }
        } else {
            unset($data['password']);
        }
        
        $model->update($data);

        if ($roleIds !== null) {
            $model->roles()->sync($roleIds);
        }

        return $model->fresh()->load('roles');
    }

    public function delete(Model $model): void
    {
        $model->roles()->detach();
        $model->permissions()->detach();
        $model->delete();
    }

    public function getAll()
    {
        return $this->model->with('roles')->orderBy('id', 'desc')->get();
    }
}
