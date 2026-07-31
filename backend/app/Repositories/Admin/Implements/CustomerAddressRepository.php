<?php

namespace App\Repositories\Admin\Implements;

use App\Models\CustomerAddress;
use App\Repositories\Admin\Interfaces\CustomerAddressRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CustomerAddressRepository implements CustomerAddressRepositoryInterface
{
    public function __construct(
        private readonly CustomerAddress $model
    ){}
    
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (isset($filters['customer_id']) && $filters['customer_id'] !== '') {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('receiver_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('receiver_phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    public function findById(int $id): ?CustomerAddress
    {
        return $this->model->find($id);
    }

    public function create(array $data): CustomerAddress
    {
        // Nếu set là mặc định thì reset các address khác của customer này
        if (!empty($data['is_default']) && !empty($data['customer_id'])) {
            $this->model->where('customer_id', $data['customer_id'])->update(['is_default' => false]);
        }
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): CustomerAddress
    {
        if (!empty($data['is_default'])) {
            $this->model->where('customer_id', $model->customer_id)
                ->where('id', '!=', $model->id)
                ->update(['is_default' => false]);
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
}
