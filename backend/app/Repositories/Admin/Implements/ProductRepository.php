<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Product;
use App\Repositories\Admin\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Product $model
    ) {}

    /**
     * Truy vấn danh sách có filter + phân trang.
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['search'].'%')
                    ->orWhere('brand', 'like', '%'.$filters['search'].'%');
            });
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        return $query
            ->with(['productImages', 'productVariants.attributeValues'])
            ->orderBy('id', 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Tìm theo ID.
     */
    public function findById(int $id): ?Product
    {
        return $this->model->find($id);
    }

    /**
     * INSERT bản ghi mới.
     */
    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    /**
     * UPDATE bản ghi, trả về dữ liệu mới nhất từ DB.
     */
    public function update(Model $model, array $data): Product
    {
        $model->update($data);

        return $model->fresh();
    }

    /**
     * DELETE bản ghi.
     */
    public function delete(Model $model): void
    {
        // Thêm hậu tố vào slug để tránh lỗi trùng lặp khi tạo sản phẩm mới cùng tên
        $model->slug = $model->slug . '-deleted-' . time();
        $model->save();

        $model->delete();
    }

    public function getStats(): array
    {
        $total = $this->model->count();
        $active = $this->model->where('is_active', true)->count();

        $outOfStock = $this->model->whereDoesntHave('productVariants', function ($q) {
            $q->where('stock_quantity', '>', 0);
        })->count();

        $newThisMonth = $this->model->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'out_of_stock' => $outOfStock,
            'new_this_month' => $newThisMonth,
        ];
    }
}
