<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
       'module',
       'action'
    ];
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }
    public function staff():BelongsToMany 
    {
        return $this ->belongsTomany(Staff::class, 'staff_permissions', 'permission_id', 'staff_id');
    }
}
