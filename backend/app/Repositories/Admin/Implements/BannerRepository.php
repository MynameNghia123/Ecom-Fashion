<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Banner;
use App\Repositories\Admin\Interfaces\BannerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class BannerRepository implements BannerRepositoryInterface
{
    public function __construct(
        private readonly Banner $model
    ) {}

    /**
     * Truy vấn danh sách banner có filter + phân trang.
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['position'])) {
            $query->where('position', $filters['position']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query->orderBy('display_order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Tìm banner theo ID.
     */
    public function findById(int $id): ?Banner
    {
        return $this->model->find($id);
    }

    /**
     * Tạo mới banner.
     */
    public function create(array $data): Banner
    {
        return $this->model->create($data);
    }

    /**
     * Cập nhật banner.
     */
    public function update(Model $model, array $data): Banner
    {
        $model->update($data);

        return $model->fresh();
    }

    /**
     * Xóa banner.
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }
}
