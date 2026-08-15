<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Blog;

/**
 * Blog Repository Interface.
 */
interface BlogRepositoryInterface extends BaseRepositoryInterface
{
    public function findById(int $id): ?Blog;

    public function getAll();
}
