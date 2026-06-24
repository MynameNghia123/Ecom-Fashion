<?php

namespace App\Http\Resources\Admin\Coupon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Biến đổi dữ liệu Coupon thô thành JSON chuẩn trả về cho client.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'discount_value' => $this->discount_value,
            'price_min_order_value' => $this->price_min_order_value,
            'max_usage' => $this->max_usage,
            'used_count' => $this->used_count,
            'is_active' => $this->is_active,
            'expiry_date' => $this->expiry_date,
            'created_by_staff_id' => $this->created_by_staff_id,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
