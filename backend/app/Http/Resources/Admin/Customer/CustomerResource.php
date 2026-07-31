<?php

namespace App\Http\Resources\Admin\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\CustomerAddress\CustomerAddressResource;

class CustomerResource extends JsonResource
{
    /**
     * Biến đổi dữ liệu Customer thô thành JSON chuẩn trả về cho client.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'first_name'       => $this->first_name,
            'last_name'       => $this->last_name,
            'email' => $this->email,
            'phone_number'  => $this->phone_number,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
            'default_address' => new CustomerAddressResource($this->whenLoaded('defaultAddress')),
        ];
    }
}
