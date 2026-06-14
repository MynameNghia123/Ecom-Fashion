<?php 

namespace App\Services\Admin\Implements;

use App\Models\Category;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use App\Services\Admin\Interfaces\CategoryServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->categoryRepository->paginate($filters);
    }

    public function create(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }

    public function update(Model $model, array $data): Category
    {
        return $this->categoryRepository->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->categoryRepository->delete($model);
    }

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }
}