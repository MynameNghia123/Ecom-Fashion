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
    ){}
    
    /**
     * Truy vấn danh sách có filter + phân trang.
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])){
            $query->where('name', 'like',  $filters['search'] . '%');
        }

        return $query
            ->with(['productImages', 'productVariants.attributeValues'])
            ->orderBy('id', 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    
    /**
     * Tìm theo ID.
     */
    public function findById(int $id): ?Product{
        return $this->model->find($id);
    }

    /**
     * INSERT bản ghi mới.
     */
    public function create(array $data) :Product{
        return $this->model->create($data);
    }

    /**
     * UPDATE bản ghi, trả về dữ liệu mới nhất từ DB.
     */
    public function update(Model $model, array $data) :Product
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
}

?>