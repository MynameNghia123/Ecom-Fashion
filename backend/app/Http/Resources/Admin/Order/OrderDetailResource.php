<?php

namespace App\Http\Resources\Admin\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Biến đổi dữ liệu OrderDetail thô thành JSON chuẩn trả về cho client.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'order_id'           => $this->order_id,
            'product_variant_id' => $this->product_variant_id,
            'product_name'       => $this->productVariant?->product?->name,
            'product_image'      => $this->productVariant?->thumbnail,
            'sku'                => $this->productVariant?->sku,
            'quantity'           => $this->quantity,
            'unit_price'         => $this->unit_price,
            'cost_price'         => $this->cost_price,
            'subtotal'           => (float) ($this->unit_price * $this->quantity),
            'is_return'          => $this->is_return,
            'return_quantity'    => $this->return_quantity,
            'return_request_id'  => $this->return_request_id,
        ];
    }
}
