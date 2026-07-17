<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    protected $fillable = [
        'module',
        'action',
    ];

    public function RolePermissions() : HasMany
    {
        return $this->hasMany(RolePermissions::class);
    }

    public function StaffPermissions() : HasMany
    {
        return $this->hasMany(StaffPermissions::class);
    }
}
