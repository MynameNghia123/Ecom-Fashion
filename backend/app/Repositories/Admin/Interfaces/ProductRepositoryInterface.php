<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Product Repository Interface — extends Base, thêm các method đặc thù của Product.
 * Dùng return type cụ thể (Product) thay vì Model chung.
 */
interface ProductRepositoryInterface extends BaseRepositoryInterface
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
    public function findById(int $id): ?Product;

    /**
     * {@inheritdoc}
     *
     * @param  array{ name: string, description: string, price: float, stock: int }  $data
     */
    public function create(array $data): Product;

    /**
     * {@inheritdoc}
     *
     * @param  array{ name: string, description: string, price: float, stock: int }  $data
     */
    public function update(Model $model, array $data): Product;

    /**
     * Lấy thống kê cho sản phẩm.
     */
    public function getStats(): array;
}
