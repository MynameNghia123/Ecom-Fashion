<?php

namespace App\Services\Admin\Interfaces;

use Illuminate\Database\Eloquent\Model;
use App\Models\Banner;

/**
 * Banner Service Interface.
 */
interface BannerServiceInterface extends BaseServiceInterface
{
    public function create(array $data): Banner;
    public function update(Model $model, array $data): Banner;
    public function getAll();
}
