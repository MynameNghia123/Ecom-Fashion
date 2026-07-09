<?php 

namespace App\Services\Admin\Implements;

use App\Models\Blog;
use App\Repositories\Admin\Interfaces\BlogRepositoryInterface;
use App\Services\Admin\Interfaces\BlogServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class BlogService implements BlogServiceInterface
{
    public function __construct(
        private readonly BlogRepositoryInterface $blogRepository
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->blogRepository->paginate($filters);
    }

    public function create(array $data): Blog
    {
        return $this->blogRepository->create($data);
    }

    public function update(Model $model, array $data): Blog
    {
        return $this->blogRepository->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->blogRepository->delete($model);
    }

    public function getAll()
    {
        return $this->blogRepository->getAll();
    }
}