<?php
namespace App\Repositories\Admin\Implements;

use App\Models\ProductImage;
use App\Repositories\Admin\Interfaces\ProductImageRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    public function __construct(
        private readonly ProductImage $model
    ){}

    /**
     * Tìm theo ID.
     */
    public function findById(int $id): ?ProductImage
    {
        return $this->model->find($id);
    }

    /**
     * INSERT bản ghi mới.
     */
    public function create(array $data): ProductImage
    {
        return $this->model->create($data);
    }

    /**
     * UPDATE bản ghi, trả về dữ liệu mới nhất từ DB.
     */
    public function update(Model $model, array $data): ProductImage
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