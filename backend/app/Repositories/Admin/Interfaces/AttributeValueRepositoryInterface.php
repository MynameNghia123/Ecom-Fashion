<?php
namespace App\Repositories\Admin\Interfaces;

use App\Models\AttributeValue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
/**
 * AttributeValue Repository Interface — extends Base, thêm các method đặc thù của AttributeValue.
 * Dùng return type cụ thể (AttributeValue) thay vì Model chung.
 */
interface AttributeValueRepositoryInterface 
{
    public function insertMany(array $data): bool;
    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?AttributeValue;

    /**
     * {@inheritdoc}
     *
     * @param array{ attribute_id: int, value: string } $data
     */
    public function create(array $data): AttributeValue;

    /**
     * {@inheritdoc}
     *
     * @param array{ attribute_id: int, value: string } $data
     */
    public function update(\Illuminate\Database\Eloquent\Model $model, array $data): AttributeValue;

    /**
     * {@inheritdoc}
     */
    public function delete(\Illuminate\Database\Eloquent\Model $model): void;

}