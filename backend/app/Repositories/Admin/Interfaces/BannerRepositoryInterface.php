<?php

namespace App\Repositories\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use App\Models\Banner;

/**
 * Banner Repository Interface.
 */
interface BannerRepositoryInterface extends BaseRepositoryInterface
{
    public function findById(int $id): ?Banner;
    public function getAll();
}
