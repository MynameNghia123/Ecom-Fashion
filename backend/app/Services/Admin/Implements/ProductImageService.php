<?php
namespace App\Services\Admin\Implements;

use App\Models\ProductImage;
use App\Repositories\Admin\Interfaces\ProductImageRepositoryInterface;
use App\Services\Admin\Interfaces\ProductImageServiceInterface;
use Illuminate\Database\Eloquent\Model;

class ProductImageService implements ProductImageServiceInterface
{
    public function __construct(
        private readonly ProductImageRepositoryInterface $repo
    ){}

    public function create(array $data): ProductImage
    {
        return $this->repo->create($data);
    }

    public function update(Model $model, array $data): ProductImage
    {
        return $this->repo->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }
   
    public function insertMany(array $data,int $productId): void
    {
        if (empty($data)) return;

        $now = now();

        $prepareData = array_map(function ($images) use ($productId, $now){
            $images['product_id'] = $productId;
            $images['created_at'] = $now;

            return $images;
        }, $data);

        $this->repo->insertMany($prepareData);
    }

    public function syncImages(Model $product, array $images): void
    {
        // Lấy tất cả images hiện có của product, đánh index theo id
        $existingImages = $product->productImages->keyBy('id');
        $keptImageIds   = [];

        foreach ($images as $imageData) {
            $imageId = $imageData['id'] ?? null;
            unset($imageData['id']);

            if ($imageId && $existingImages->has($imageId)) {
                // ── UPDATE image cũ ───────────────────────────────────────
                $imageModel = $existingImages->get($imageId);
                $this->repo->update($imageModel, $imageData);
                $keptImageIds[] = $imageId;
            } else {
                // ── CREATE image mới ──────────────────────────────────────
                $imageData['product_id'] = $product->id;
                $newImage = $this->repo->create($imageData);
                $keptImageIds[] = $newImage->id;
            }
        }

        // ── DELETE images không còn trong danh sách ───────────────────────
        foreach ($existingImages as $oldImage) {
            if (!in_array($oldImage->id, $keptImageIds)) {
                $this->repo->delete($oldImage);
            }
        }
    }
}
