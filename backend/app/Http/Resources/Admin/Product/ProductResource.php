<?php
namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\Product\ProductVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Biến đổi dữ liệu Attribute thô thành JSON chuẩn trả về cho client.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 										created_at			

        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'brand' => $this->brand,
            'thumbnail' => $this->thumbnail,
            'user_manual' => $this->user_manual,
            'is_active' => $this->is_active,


            // có nhiều image
            'images' => ProductImageResource::collection($this->whenLoaded('productImages')),

            // có nhiều biến thể
            'variants' => ProductVariantResource::collection($this->whenLoaded('productVariants')),

            'created_by_staff_id' => $this->created_by_staff_id,
            'updated_by_staff_id' => $this->updated_by_staff_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}