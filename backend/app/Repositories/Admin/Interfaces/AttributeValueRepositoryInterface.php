<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Model;

/**
 * AttributeValue Repository Interface — extends Base, thêm các method đặc thù của AttributeValue.
 * Dùng return type cụ thể (AttributeValue) thay vì Model chung.
 */
interface AttributeValueRepositoryInterface
{
    public function insertMany(array $data): bool;

    public function findById(int $id): ?AttributeValue;

    /**
     * @param  array{ attribute_id: int, value: string }  $data
     */
    public function create(array $data): AttributeValue;

    /**
     * @param  array{ attribute_id: int, value: string }  $data
     */
    public function update(Model $model, array $data): AttributeValue;

    public function delete(Model $model): void;
}
