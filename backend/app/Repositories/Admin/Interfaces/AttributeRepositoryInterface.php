<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Attribute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Attribute Repository Interface — extends Base, thêm các method đặc thù của Attribute.
 * Dùng return type cụ thể (Attribute) thay vì Model chung.
 */
interface AttributeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param  array{ search?: string, per_page?: int }  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator;

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Attribute;

    /**
     * {@inheritdoc}
     *
     * @param  array{ name: string }  $data
     */
    public function create(array $data): Attribute;

    /**
     * {@inheritdoc}
     *
     * @param  array{ name: string }  $data
     */
    public function update(Model $model, array $data): Attribute;
}
