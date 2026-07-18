<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BlogRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?Blog;
    public function create(array $data): Blog;
    function update(Model $model, array $data): Blog;
}
