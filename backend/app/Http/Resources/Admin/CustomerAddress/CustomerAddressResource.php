<?php

namespace App\Http\Resources\Admin\CustomerAddress;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Customer\CustomerResource;

class CustomerAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'customer_id'    => $this->customer_id,
            'receiver_name'  => $this->receiver_name,
            'receiver_phone' => $this->receiver_phone,
            'province'       => $this->province,
            'district'       => $this->district,
            'ward'           => $this->ward,
            'detail_address' => $this->detail_address,
            'is_default'     => (bool) $this->is_default,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'customer'       => new CustomerResource($this->whenLoaded('customer')),
        ];
    }
}
