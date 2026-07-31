<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Customer;
use App\Repositories\Admin\Interfaces\CustomerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        private readonly Customer $model
    ){}
    
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone_number', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (int) $filters['status']);
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }
    public function findById(int $id): ?Customer
    {
        return $this->model->find($id);
    }
    public function create(array $data): Customer
    {
        return $this->model->create($data);
    }
    public function update(Model $model, array $data): Customer
    {
        if (isset($data['password'])) {
            if (empty($data['password'])) {
                unset($data['password']);
            }
        } else {
            unset($data['password']);
        }
        
        $model->update($data);

        return $model->fresh();
    }   
    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    public function getStats(): array
    {
        return [
            'total_active' => $this->model->where('status', 1)->count(),
            'total_banned' => $this->model->where('status', 0)->count(),
            'new_today'    => $this->model->whereDate('created_at', today())->count(),
        ];
    }

}
?>