<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface RoleServiceInterface
 * * Định nghĩa các hợp đồng nghiệp vụ (Business Logic) cho thực thể Vai trò (Role).
 * * @package App\Services\Admin\Interfaces
 */
interface RoleServiceInterface 
{
    /**
     * Lấy danh sách vai trò có phân trang và bộ lọc.
     * * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getList(array $filters): LengthAwarePaginator;

    /**
     * Tạo mới vai trò và đồng bộ quyền hạn đi kèm.
     * * @param array $data
     * @return Role
     */
    public function create(array $data): Role;

    /**
     * Cập nhật thông tin vai trò và làm mới danh sách quyền hạn.
     * * @param Model $model
     * @param array $data
     * @return Role
     */
    public function update(Model $model, array $data): Role;

    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }
    
    public function insertMany(array $attributesData, $variantId) : void
    {
        if (empty($attributesData))
            return;
        
        // Chỉ giữ các field hợp lệ của bảng attribute_values
        $allowedFields = ['product_variant_id', 'attribute_id', 'value'];

        $prepareAttribute = array_map(function ($attribute) use ($variantId, $allowedFields){
            $attribute['product_variant_id'] = $variantId;

            return array_intersect_key($attribute, array_flip($allowedFields));
        }, $attributesData);

        $this->repo->insertMany($prepareAttribute);
    }
    public function syncAttributes(Model $variant, array $attributeValues): void
    {
        // Lấy tất cả attribute_values hiện có của variant, đánh index theo id
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
