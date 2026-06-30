<?php
namespace App\Repositories\Admin\Implements;

use App\Models\GoodReceipt;
use App\Repositories\Admin\Interfaces\GoodReceiptRepoInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptRepo implements GoodReceiptRepoInterface
{
    public function __construct(
        private readonly GoodReceipt $model
    ){}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])){
            $keyword = $filters['search'];

            $query->where(function ($q) use ($keyword){
                // $q->where('name', 'like', $keyword. '%')
                //     ->orWhere('phone', 'like', $keyword . '%')
                //     ->orWhere('address', 'like', $keyword. '%')
                //     ->orWhere('email', 'like', $keyword. '%');
            });
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 4);
    }

    public function findById(int $id): ?GoodReceipt
    {
        return $this->model->find($id);
    }


    public function create(array $data): GoodReceipt
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): GoodReceipt
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }
}