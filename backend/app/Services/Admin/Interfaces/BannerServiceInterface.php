<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Model;

/**
 * Banner Service Interface.
 */
interface BannerServiceInterface extends BaseServiceInterface
{
    public function create(array $data): Banner;

    public function update(Model $model, array $data): Banner;

    public function getAll();
}
