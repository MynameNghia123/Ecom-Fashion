<?php
namespace App\Services\Admin\Interfaces;

use App\Models\Category;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use \Illuminate\Database\Eloquent\Model;

interface CategoryServiceInterface extends BaseServiceInterface
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
     * @param array{ name: string, parent_id?: int } $data
     */
    public function create(array $data): Category;

    /**
     * {@inheritdoc}
     *
     * @param array{ name: string, parent_id?: int } $data
     */
    public function update(Model $model, array $data): Category;
}
?>