<?php
namespace App\Http\Resources\Admin\GoodReceipt;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodReceiptDetailResource extends JsonResource
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
            'goods_receipt_id' => $this->goods_receipt_id,
            'product_variant_id' => $this->product_variant_id,
            'quantity' => $this->quantity,
            'import_price' => $this->import_price,
        ];
    }
}