<?php

namespace App\Http\Resources\Admin\GoodReceipt;

use App\Http\Resources\Admin\GoodReceipt\GoodReceiptDetailResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodReceiptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'receipt_code' => $this->receipt_code,
            'supplier_id' => $this->supplier_id,
            'staff_id' => $this->staff_id,
            'total_amount_price' => $this->total_amount_price,
            'status' => $this->status,

            'good_receipt_details' => GoodReceiptDetailResource::collection($this->whenLoaded('goodReceiptDetail')),

            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
