<?php

namespace App\Repositories\Admin\Implements;

use App\Models\ProductVariant;
use App\Repositories\Admin\Interfaces\ProductVariantRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ProductVariantRepository implements ProductVariantRepositoryInterface
{
    public function __construct(
        private readonly ProductVariant $model
    ) {}

    /**
     * Tìm theo ID.
     */
    public function findById(int $id): ?ProductVariant
    {
        return $this->model->find($id);
    }

    /**
     * INSERT bản ghi mới.
     */
    public function create(array $data): ProductVariant
    {
        return $this->model->create($data);
    }

    /**
     * UPDATE bản ghi, trả về dữ liệu mới nhất từ DB.
     */
    public function update(Model $model, array $data): ProductVariant
    {
        $model->update($data);

        return $model->fresh();
    }

    /**
     * DELETE bản ghi.
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

    /**
     * Search product variants by SKU or ID
     */
    public function searchBySkuOrId(string $query)
    {
        return $this->model
            ->with(['product:id,name'])
            ->where('sku', 'like', '%'.$query.'%')
            ->orWhere('id', $query)
            ->orWhereHas('product', function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%');
            })
            ->limit(20)
            ->get();
    }

    /**
     * Increment stock quantity of a product variant
     */
    public function incrementStock(int $id, int $quantity): void
    {
        $this->model->where('id', $id)->increment('stock_quantity', $quantity);
    }
}
