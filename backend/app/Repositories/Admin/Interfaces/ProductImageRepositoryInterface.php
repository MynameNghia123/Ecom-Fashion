<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;

/**
 * ProductImage Repository Interface — extends Base, thêm các method đặc thù của ProductImage.
 * Dùng return type cụ thể (ProductImage) thay vì Model chung.
 */
interface ProductImageRepositoryInterface
{
    public function findById(int $id): ?ProductImage;

    /**
     * @param  array{ product_id: int, image_path: string }  $data
     */
    public function create(array $data): ProductImage;

    public function insertMany(array $data): bool;

    /**
     * @param  array{ product_id: int, image_path: string }  $data
     */
    public function update(Model $model, array $data): ProductImage;

    public function delete(Model $model): void;
}
