<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Role Model
 * Schema: roles(id, name, description, created_at, updated_at)
 * Bảng trung gian:
 *   role_permissions(role_id, permission_id) — composite PK
 *   staff_roles(staff_id, role_id) — composite PK
 */
class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Role có nhiều Permission (qua bảng trung gian role_permissions).
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',  // bảng trung gian
            'role_id',           // FK trỏ về roles
            'permission_id'      // FK trỏ về permissions
        );
    }

    /**
     * Role được gán cho nhiều Staff (qua bảng trung gian staff_roles).
     * Schema: staff_roles(staff_id, role_id) — composite PK
     */
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(
            Staff::class,
            'staff_roles',  // đúng theo schema (không phải 'role_staff')
            'role_id',
            'staff_id'
        );
    }
}
