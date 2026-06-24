<?php

namespace App\Services\Admin\Implements;

use App\Models\AttributeValue;
use App\Repositories\Admin\Interfaces\AttributeValueRepositoryInterface;
use App\Services\Admin\Interfaces\AttributeValueServiceInterface;
use Illuminate\Database\Eloquent\Model;

class AttributeValueService implements AttributeValueServiceInterface
{
    public function __construct(
        private readonly AttributeValueRepositoryInterface $repo
    ) {}

    /**
     * Tạo mới một attribute_value.
     */
    public function create(array $data): AttributeValue
    {
        return $this->repo->create($data);
    }

    /**
     * Cập nhật attribute_value.
     */
    public function update(Model $model, array $data): AttributeValue
    {
        return $this->repo->update($model, $data);
    }

    /**
     * Xóa attribute_value.
     */
    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }

    /**
     * Thêm nhiều attribute_values cùng lúc (bulk insert) cho một variant.
     */
    public function insertMany(array $attributesData, $variantId): void
    {
        if (empty($attributesData)) {
            return;
        }

        $prepareAttribute = array_map(function ($attribute) use ($variantId) {
            $attribute['product_variant_id'] = $variantId;
            return $attribute;
        }, $attributesData);

        $this->repo->insertMany($prepareAttribute);
    }

    /**
     * Đồng bộ attribute_values của một variant:
     * - UPDATE nếu id đã tồn tại
     * - CREATE nếu là attribute mới
     * - DELETE nếu không còn trong danh sách
     */
    public function syncAttributes(Model $variant, array $attributeValues): void
    {
        $existingAttributes = $variant->attributeValues->keyBy('id');
        $keptAttributeIds   = [];

        foreach ($attributeValues as $attrData) {
            $attrId = $attrData['id'] ?? null;
            unset($attrData['id']);

            if ($attrId && $existingAttributes->has($attrId)) {
                // ── UPDATE attribute_value cũ ──────────────────────────────
                $attrModel = $existingAttributes->get($attrId);
                $this->repo->update($attrModel, $attrData);
                $keptAttributeIds[] = $attrId;
            } else {
                // ── CREATE attribute_value mới ─────────────────────────────
                $attrData['product_variant_id'] = $variant->id;
                $newAttr = $this->repo->create($attrData);
                $keptAttributeIds[] = $newAttr->id;
            }
        }

        // ── DELETE attribute_values không còn trong danh sách ─────────────
        foreach ($existingAttributes as $oldAttr) {
            if (!in_array($oldAttr->id, $keptAttributeIds)) {
                $this->repo->delete($oldAttr);
            }
        }
    }
}