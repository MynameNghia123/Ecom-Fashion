<?php
namespace App\Repositories\Admin\Implements;

use App\Models\GoodReceiptDetail;
use App\Repositories\Admin\Interfaces\GoodReceiptDetailRepoInterface;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptDetailRepo implements GoodReceiptDetailRepoInterface
{
    public function __construct(
        private readonly GoodReceiptDetail $model
    ){}

    public function create(array $data): GoodReceiptDetail
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): GoodReceiptDetail
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function insertMany($data): bool
    {
        if (empty($data)) return false;
        return $this->model->insert($data);
    }

}