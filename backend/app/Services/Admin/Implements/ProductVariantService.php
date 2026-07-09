<?php
namespace App\Services\Admin\Implements;

use App\Models\ProductVariant;
use App\Repositories\Admin\Interfaces\ProductVariantRepositoryInterface;
use App\Services\Admin\Interfaces\AttributeValueServiceInterface;
use App\Services\Admin\Interfaces\ProductVariantServiceInterface;
use Illuminate\Database\Eloquent\Model;

class ProductVariantService implements ProductVariantServiceInterface
{
    public function __construct(
        private readonly ProductVariantRepositoryInterface $repo,
        private readonly AttributeValueServiceInterface $attributeValueService,
    ){}

    public function create(array $data): ProductVariant
    {
        return $this->repo->create($data);
    }

    public function update(Model $model, array $data): ProductVariant
    {
        return $this->repo->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }

    public function syncVariants(Model $model,array $data) : void
    {
        $existingVariants = $model->productVariants->keyBy('id');
        $keptVariantIds = [];

        foreach ($data as $variantData)
        {
            $attributeData = $variantData['attribute_values'] ?? [];
            unset($variantData['attribute_values']);

            $variantId = $variantData['id'] ?? null;
            unset($variantData['id']);

            if ($variantId && $existingVariants->has($variantId)) {
                // ── UPDATE variant cũ ─────────────────────────────────────
                $variantModel = $existingVariants->get($variantId);
                $this->repo->update($variantModel, $variantData);
                $keptVariantIds[]    = $variantId;
                $currentVariantModel = $variantModel;
            } else {
                // ── CREATE variant mới ────────────────────────────────────
                $variantData['product_id'] = $model->id;
                $newVariantModel           = $this->repo->create($variantData);
                $keptVariantIds[]          = $newVariantModel->id;
                $currentVariantModel       = $newVariantModel; // ← fix: trước đây gán nhầm $variantModel (undefined)
            }
            $this->attributeValueService->syncAttributes($currentVariantModel, $attributeData);
        }
        foreach ($existingVariants as $oldVariantModel) {
            if (!in_array($oldVariantModel->id, $keptVariantIds)) {
                
                // (Nếu DB bạn không có onDelete('cascade'), bạn phải gọi 
                // attributeValueService xóa thuộc tính con trước khi gọi dòng dưới)
                
                // Giao Object Model cho Repo đi hủy diệt
                $this->repo->delete($oldVariantModel);
            }
        }
    }

    public function insertMany(array $variantsData, int $productId) : void{
        if (empty($variantsData)){
            return;
        }

        foreach ($variantsData as $variantData){
            $attributeValue = $variantData['attribute_values'] ?? [];

            unset($variantData['attribute_values']);

            $variantData['product_id'] = $productId;

            $createdVariant = $this->repo->create($variantData);

            if (!empty($attributeValue)) {
                $this->attributeValueService->insertMany(
                    $attributeValue,
                    $createdVariant->id
                );
            }
        }
    }

    public function searchBySkuOrId(string $query)
    {
        return $this->repo->searchBySkuOrId($query);
    }
}
