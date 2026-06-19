<?php
namespace App\Services\Admin\Interfaces;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;

interface ProductVariantServiceInterface 
{
    public function insertMany(array $variantsData, int $productId) : void;
    public function syncVariants(Model $model, array $data) : void;
    /**
     * {@inheritdoc}
     *
     * @param array{ name: string, price: float, description?: string } $data
     */
    public function create(array $data): ProductVariant;

    /**
     * {@inheritdoc}
     *
     * @param array{ name?: string, price?: float, description?: string } $data
     */
    public function update(Model $model, array $data): ProductVariant;

    public function delete(Model $model): void;
    
}