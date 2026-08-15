<?php

namespace App\Services\Admin\Implements;

use App\Models\Banner;
use App\Repositories\Admin\Interfaces\BannerRepositoryInterface;
use App\Services\Admin\Interfaces\BannerServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class BannerService implements BannerServiceInterface
{
    public function __construct(
        private readonly BannerRepositoryInterface $bannerRepository
    ) {}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->bannerRepository->paginate($filters);
    }

    public function create(array $data): Banner
    {
        return $this->bannerRepository->create($data);
    }

    public function update(Model $model, array $data): Banner
    {
        return $this->bannerRepository->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->bannerRepository->delete($model);
    }

    public function getAll()
    {
        return $this->bannerRepository->getAll();
    }
}
