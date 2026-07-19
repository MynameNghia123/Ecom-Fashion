<?php

namespace App\Http\Resources\Admin\ReturnRequest;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnRequestResource extends JsonResource
{
    /**
     * Biến đổi dữ liệu ReturnRequest thô thành JSON chuẩn trả về cho client.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'order_id'              => $this->order_id,
            'order_code'            => $this->order?->order_code,
            'customer_name'         => $this->order?->customer?->full_name ?? $this->order?->shipping_name,
            'reason'                => $this->reason,
            'evidence_images'       => $this->evidence_images ?? [],
            'status'                => $this->status,
            'refund_amount'         => $this->refund_amount,
            'processed_by_staff_id' => $this->processed_by_staff_id,
            'processed_by_staff'    => $this->processedByStaff?->full_name,
            'order_details'         => $this->whenLoaded('orderDetails'),
            'created_at'            => $this->created_at?->format('d/m/Y H:i'),
            'updated_at'            => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
