<?php
namespace App\Repositories\Admin\Interfaces;
use App\Models\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
/**
 * ProductVariant Repository Interface — extends Base, thêm các method đặc thù của ProductVariant.
 * Dùng return type cụ thể (ProductVariant) thay vì Model chung.
 */
interface ProductVariantRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?ProductVariant;

    /**
     * {@inheritdoc}
     *
     * @param array{ product_id: int, attribute_value_ids: array<int>, price: float, stock: int } $data
     */
    public function create(array $data): ProductVariant;

    /**
     * {@inheritdoc}
     *
     * @param array{ product_id: int, attribute_value_ids: array<int>, price: float, stock: int } $data
     */
    public function update(\Illuminate\Database\Eloquent\Model $model, array $data): ProductVariant;

    public function delete(\Illuminate\Database\Eloquent\Model $model): void;
    
    /**
     * Search product variants by SKU or ID
     */
    public function searchBySkuOrId(string $query);
}   