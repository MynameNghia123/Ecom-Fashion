<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermissions extends Model
{
    protected $fillable = [
        'role_id',
        'permission_id',
    ];    

    public function Role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function Permission() : BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
