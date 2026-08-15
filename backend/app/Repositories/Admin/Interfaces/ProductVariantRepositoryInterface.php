<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;

/**
 * ProductVariant Repository Interface — extends Base, thêm các method đặc thù của ProductVariant.
 * Dùng return type cụ thể (ProductVariant) thay vì Model chung.
 */
interface ProductVariantRepositoryInterface
{
    public function findById(int $id): ?ProductVariant;

    /**
     * @param  array{ product_id: int, attribute_value_ids: array<int>, price: float, stock: int }  $data
     */
    public function create(array $data): ProductVariant;

    /**
     * @param  array{ product_id: int, attribute_value_ids: array<int>, price: float, stock: int }  $data
     */
    public function update(Model $model, array $data): ProductVariant;

    public function delete(Model $model): void;

    /**
     * Search product variants by SKU or ID
     */
    public function searchBySkuOrId(string $query);

    /**
     * Increment stock quantity of a product variant
     */
    public function incrementStock(int $id, int $quantity): void;
}
