<?php
namespace App\Services\Admin\Interfaces;

use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Model;

interface AttributeValueServiceInterface 
{
    public function syncAttributes(Model $currentVariantModel, array $attributeData) : void;
    public function insertMany(array $attributesData, $variantId) : void;
    /**
     * {@inheritdoc}
     *
     * @param array{ value: string } $data
     */
    public function create(array $data): AttributeValue;

    /**
     * {@inheritdoc}
     *
     * @param array{ value?: string } $data
     */
    public function update(Model $model, array $data): AttributeValue;

    public function delete(Model $model): void;
}