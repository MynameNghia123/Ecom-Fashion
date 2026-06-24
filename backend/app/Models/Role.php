<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at'
    ];
    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }
    public function staff():BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'role_staff', 'role_id', 'staff_id');
    }
}
