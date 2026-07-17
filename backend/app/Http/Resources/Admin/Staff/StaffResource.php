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
            'last_login_at' => $this->last_login_at ? $this->last_login_at->toDateTimeString() : null,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'role_ids' => $this->whenLoaded('StaffRoles', function () {
                return $this->StaffRoles->pluck('role_id');
            }),
            'permission_ids' => $this->whenLoaded('StaffPermissions', function () {
                return $this->StaffPermissions->pluck('permission_id');
            }),
        ];
    }
}
