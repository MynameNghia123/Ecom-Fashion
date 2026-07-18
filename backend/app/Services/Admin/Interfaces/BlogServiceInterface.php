<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use \Illuminate\Database\Eloquent\Model;

interface BlogServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function create(array $data): Blog;
    public function update(Model $model, array $data): Blog;
}
