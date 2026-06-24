<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Staff extends Authenticatable
{
    protected $table = 'staff';
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone_number',
        'avatar',
        'is_active',
        'last_login_at',
    ];
    protected function casts(): array {
        return [
            'password'=> 'hashed',
            'is_active' => 'boolean',
        ];
    }
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'staff_roles', 'staff_id', 'role_id');
    }
    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'staff_permissions', 'staff_id', 'permission_id');
    }   
    // kiem tra quyen han nhan vien 
    public function hasPermission (string $module, string $action ) : bool
    {
        if (isset($this->is_active) && !$this->is_active) {
            return false;
        }
        if ($this->roles->pluck('name')->contains('admin ')) {
            return true;
        }
        $hasDirect = $this->directPermissions->contains(function ($permission) use ($module, $action) {
            return $permission->module === $module && $permission->action === $action;
        });
        if ($hasDirect) {
            return true;
        }
        foreach ($this->roles as $role) {
            $hasRolePermission = $role->permissions->contains(function ($permission) use ($module, $action) {
                return $permission->module === $module && $permission->action === $action;
            });

            if ($hasRolePermission) {
                return true;
            }
        }
        return false;
    }

}
