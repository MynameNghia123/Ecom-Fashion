<?php

namespace App\Http\Resources\Admin\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Biến đổi dữ liệu Order thô thành JSON chuẩn trả về cho client.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'order_code'             => $this->order_code,
            'customer_id'            => $this->customer_id,
            'customer_name'          => $this->customer?->full_name,
            'customer_email'         => $this->customer?->email,
            'coupon_id'              => $this->coupon_id,
            'coupon_code'            => $this->coupon?->code,
            'shipping_name'          => $this->shipping_name,
            'shipping_phone'         => $this->shipping_phone,
            'shipping_address'       => $this->shipping_address,
            'sub_total_amount'       => $this->sub_total_amount,
            'coupon_discount_amount' => $this->coupon_discount_amount,
            'shipping_fee'           => $this->shipping_fee,
            'final_amount'           => $this->final_amount,
            'status'                 => $this->status,
            'payment_method'         => $this->payment_method,
            'payment_status'         => $this->payment_status,
            'transaction_id'         => $this->transaction_id,
            'order_details'          => OrderDetailResource::collection($this->whenLoaded('toPoint')),
            'created_at'             => $this->created_at?->format('d/m/Y H:i'),
            'updated_at'             => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
