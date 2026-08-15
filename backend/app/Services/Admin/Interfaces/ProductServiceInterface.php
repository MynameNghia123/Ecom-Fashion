<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ProductServiceInterface extends BaseServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @param  array{ search?: string, per_page?: int }  $filters
     */
    public function getList(array $filters): LengthAwarePaginator;

    /**
     * {@inheritdoc}
     *
     * @param  array{ name: string, price: float, description?: string }  $data
     */
    public function create(array $data): Product;

    /**
     * {@inheritdoc}
     *
     * @param  array{ name?: string, price?: float, description?: string }  $data
     */
    public function update(Model $model, array $data): Product;

    /**
     * Lấy thống kê cho sản phẩm.
     */
    public function getStats(): array;
}
