<?php

namespace App\Http\Resources\Admin\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * PermissionResource — transform Permission model thành JSON.
 * Schema: permissions(id, module, action) — composite unique(module, action)
 */
class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'module' => $this->module,
            'action' => $this->action,
            // Label dễ đọc cho UI: "products.view"
            'label'  => "{$this->module}.{$this->action}",
        ];
    }
}
