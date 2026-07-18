<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Banner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface BannerRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?Banner;
    public function create(array $data): Banner;
    public function update(Model $model, array $data): Banner;
    public function delete(Model $model): void;
}
