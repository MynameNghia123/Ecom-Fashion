<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Category Repository Interface — extends Base, thêm các method đặc thù của Category.
 * Dùng return type cụ thể (Category) thay vì Model chung.
 */
interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param  array{ search?: string, per_page?: int }  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator;

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Category;

    /**
     * {@inheritdoc}
     *
     * @param  array{ name: string }  $data
     */
    public function create(array $data): Category;

    /**
     * {@inheritdoc}
     *
     * @param  array{ name: string }  $data
     */
    public function update(Model $model, array $data): Category;

    /**
     * Lấy toàn bộ danh mục không phân trang.
     *
     * @return Collection
     */
    public function getAll();

    /**
     * Lấy thống kê danh mục (tổng, cha, con)
     */
    public function getStats(): array;
}
