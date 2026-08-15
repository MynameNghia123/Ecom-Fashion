<?php

namespace App\Services\Admin\Interfaces;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;

interface ProductImageServiceInterface
{
    public function insertMany(array $data, int $productId): void;

    public function syncImages(Model $product, array $images): void;

    /**
     * @param  array{ url: string }  $data
     */
    public function create(array $data): ProductImage;

    /**
     * @param  array{ url?: string }  $data
     */
    public function update(Model $model, array $data): ProductImage;

    public function delete(Model $model): void;
}
