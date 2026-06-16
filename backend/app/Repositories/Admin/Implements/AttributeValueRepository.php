<?php
namespace App\Repositories\Admin\Interfaces;

use App\Models\AttributeValue;
use App\Repositories\Admin\Interfaces\AtributeValueRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class AttributeValueRepository implements AttributeValueRepositoryInterface
{
    public function __construct(
        private readonly AttributeValue $model
    ){}

    /**
     * Tìm theo ID.
     */
    public function findById(int $id): ?AttributeValue
    {
        return $this->model->find($id);
    }

    /**
     * INSERT bản ghi mới.
     */
    public function create(array $data): AttributeValue
    {
        return $this->model->create($data);
    }

    /**
     * UPDATE bản ghi, trả về dữ liệu mới nhất từ DB.
     */
    public function update(Model $model, array $data): AttributeValue
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