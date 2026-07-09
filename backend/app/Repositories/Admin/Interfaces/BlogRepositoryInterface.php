<?php

namespace App\Repositories\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use App\Models\Blog;

/**
 * Blog Repository Interface.
 */
interface BlogRepositoryInterface extends BaseRepositoryInterface
{
    public function findById(int $id): ?Blog;
    public function getAll();
}
