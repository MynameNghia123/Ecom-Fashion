<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;

/**
 * Blog Service Interface.
 */
interface BlogServiceInterface extends BaseServiceInterface
{
    public function create(array $data): Blog;

    public function update(Model $model, array $data): Blog;

    public function getAll();
}
