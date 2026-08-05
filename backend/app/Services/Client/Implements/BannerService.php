<?php
namespace App\Services\Client\Implements;
use App\Repositories\Client\Interfaces\BannerRepositoryInterface;
use App\Services\Client\Interfaces\BannerServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class BannerService implements BannerServiceInterface
{
    public function __construct(private readonly BannerRepositoryInterface $repo) {}

    public function getActiveBanners(?string $position = null): Collection
    {
        return $this->repo->getActiveBanners($position);
    }
}
