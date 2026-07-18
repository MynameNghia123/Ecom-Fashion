<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Banner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface BannerServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function create(array $data): Banner;
    public function update(Model $model, array $data): Banner;
    public function delete(Model $model): void;
}
