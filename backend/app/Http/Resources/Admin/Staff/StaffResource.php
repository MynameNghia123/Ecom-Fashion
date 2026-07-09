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
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(fn($r) => [
                    'id' => $r->id,
                    'name' => $r->name,
                    'description' => $r->description,
                ]);
            }),
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->permissions->map(fn($p) => [
                    'id' => $p->id,
                    'module' => $p->module,
                    'action' => $p->action,
                ]);
            }),
            'last_login_at' => $this->last_login_at?->format('d/m/Y H:i') ?? null,
            'created_at' => $this->created_at?->format('d/m/Y') ?? null,
            'updated_at' => $this->updated_at?->format('d/m/Y H:i') ?? null,
        ];
    }
}
