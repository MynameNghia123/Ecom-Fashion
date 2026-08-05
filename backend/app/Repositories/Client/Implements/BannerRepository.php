<?php
namespace App\Repositories\Client\Implements;
use App\Models\Banner;
use App\Repositories\Client\Interfaces\BannerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class BannerRepository implements BannerRepositoryInterface
{
    public function __construct(private readonly Banner $model) {}

    public function getActiveBanners(?string $position = null): Collection
    {
        $now = Carbon::now();
        $query = $this->model->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });

        if (!empty($position)) {
            $query->where('position', $position);
        }

        return $query->orderBy('display_order', 'asc')->orderBy('id', 'asc')->get();
    }
}
