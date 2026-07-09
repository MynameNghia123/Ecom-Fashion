<?php
namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\Product\AttributeValueResource;
use App\Http\Resources\Admin\Product\ProductImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'product_id' => $this->product_id,
            'sku' => $this->sku,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'cost_price' => $this->cost_price,
            'stock_quantity' => $this->stock_quantity,
            'thumbnail' => $this->thumbnail,
            'is_active' => $this->is_active,

            'attribute_values' => AttributeValueResource::collection($this->whenLoaded('attributeValues')),
            'product' => new ProductResource($this->whenLoaded('product')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}