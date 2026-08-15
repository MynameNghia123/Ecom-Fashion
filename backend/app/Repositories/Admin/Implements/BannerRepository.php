<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Banner;
use App\Repositories\Admin\Interfaces\BannerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class BannerRepository implements BannerRepositoryInterface
{
    public function __construct(
        private readonly Banner $model,
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (! empty($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        if (isset($filters['position']) && $filters['position'] !== '') {
            $query->where('position', $filters['position']);
        }

        return $query->orderBy('display_order', 'asc')->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    public function findById(int $id): ?Banner
    {
        return $this->model->find($id);
    }

    public function create(array $data): Banner
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Banner
    {
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
