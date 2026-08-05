<?php
namespace App\Services\Client\Interfaces;
use Illuminate\Database\Eloquent\Collection;

interface BannerServiceInterface
{
    public function getActiveBanners(?string $position = null): Collection;
}
