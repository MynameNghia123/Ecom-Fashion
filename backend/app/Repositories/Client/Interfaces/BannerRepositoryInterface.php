<?php

namespace App\Repositories\Client\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface BannerRepositoryInterface
{
    public function getActiveBanners(?string $position = null): Collection;
}
