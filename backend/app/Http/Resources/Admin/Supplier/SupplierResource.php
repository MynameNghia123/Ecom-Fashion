<?php
namespace App\Http\Resources\Admin\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' =>$this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}