<?php

namespace App\Http\Resources\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    /**
     * Biến đổi dữ liệu Attribute thô thành JSON chuẩn trả về cho client.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'image_url' => $this->image_url,
            'alt_text' => $this->alt_text,
            'display_order' => $this->display_order,
            'is_thumbnail' => $this->is_thumbnail,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
