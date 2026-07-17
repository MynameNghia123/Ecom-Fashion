<?php

namespace App\Http\Resources\Admin\Role;

use App\Http\Resources\Admin\Permission\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            // Trả về permissions nếu đã được load (dùng whenLoaded để tránh N+1 trong index)
            'permissions' => $this->whenLoaded('rolePermissions', function () {
                return $this->rolePermissions->map(fn ($rp) => [
                    'id'     => $rp->permission->id,
                    'module' => $rp->permission->module,
                    'action' => $rp->permission->action,
                ]);
            }),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
