<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Banner;

/**
 * Banner Repository Interface.
 */
interface BannerRepositoryInterface extends BaseRepositoryInterface
{
    public function findById(int $id): ?Banner;

    public function getAll();
}
