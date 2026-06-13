<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Attribute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use \Illuminate\Database\Eloquent\Model;
/**
 * Attribute Service Interface — extends Base, dùng return type cụ thể.
 */
interface AttributeServiceInterface extends BaseServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @param array{ search?: string, per_page?: int } $filters
     */
    public function getList(array $filters): LengthAwarePaginator;

    /**
     * {@inheritdoc}
     *
     * @param array{ name: string } $data
     */
    public function create(array $data): Attribute;

    /**
     * {@inheritdoc}
     *
     * @param array{ name: string } $data
     */
    public function update(Model $model, array $data): Attribute;
}
