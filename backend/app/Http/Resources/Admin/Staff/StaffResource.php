<?php

namespace App\Http\Resources\Admin\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'avatar' => $this->avatar,
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at?->format('d/m/Y H:i') ?? null,
            'created_at' => $this->created_at?->format('d/m/Y') ?? null,
            'updated_at' => $this->updated_at?->format('d/m/Y H:i') ?? null,
        ];
      
}
}
