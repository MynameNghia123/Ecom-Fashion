<?php

namespace App\Http\Resources\Admin\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * RoleResource — transform Role model thành JSON response.
 * Trả về danh sách permissions nếu đã được load (with('permissions')).
 */
class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            // Trả về permissions nếu đã eager loaded, nhóm theo module để FE render dễ
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->permissions->map(fn($p) => [
                    'id'     => $p->id,
                    'module' => $p->module,
                    'action' => $p->action,
                ]);
            }),
            // Số lượng staff được gán role này (nếu có load)
            'staff_count' => $this->whenLoaded('staff', fn() => $this->staff->count()),
            'created_at'  => $this->created_at?->format('d/m/Y H:i'),
            'updated_at'  => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
