<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function RolePermissions() : HasMany
    {
       return $this->hasMany(RolePermissions::class);
    }

    public function StaffRoles() : HasMany
    {
        return $this->hasMany(StaffRoles::class);
    }
}
