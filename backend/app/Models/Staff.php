<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Staff Model
 * Schema: staff(id, full_name, email, password, phone_number, avatar,
 *               is_active, last_login_at, created_at, updated_at, deleted_at)
 */
class Staff extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

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

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * Staff thuộc nhiều Role (bảng trung gian: staff_roles).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'staff_roles', 'staff_id', 'role_id');
    }

    /**
     * Staff có direct permission riêng (bảng trung gian: staff_permissions).
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'staff_permissions', 'staff_id', 'permission_id');
    }

    // ── RBAC Logic ────────────────────────────────────────────────────────────

    /**
     * Kiểm tra staff có quyền thực hiện action trên module không.
     *
     * Thứ tự ưu tiên:
     * 1. Staff bị khóa (is_active = false) → DENY
     * 2. Staff có role tên 'admin' → ALLOW ALL
     * 3. Staff có direct permission (staff_permissions) → ALLOW
     * 4. Staff có permission qua role (role_permissions) → ALLOW
     * 5. Không có → DENY
     */
    public function hasPermission(string $module, string $action): bool
    {
        // 1. Tài khoản bị khóa
        if (! $this->is_active) {
            return false;
        }

        // Load eager nếu chưa load (tránh N+1)
        $this->loadMissing(['roles.permissions', 'permissions']);

        // 2. Super admin: role có name = 'admin' (không dấu cách)
        if ($this->roles->pluck('name')->contains('admin')) {
            return true;
        }

        // 3. Direct permission trên staff_permissions
        $hasDirect = $this->permissions->contains(
            fn ($p) => $p->module === $module && $p->action === $action
        );
        if ($hasDirect) {
            return true;
        }

        // 4. Permission thông qua role
        foreach ($this->roles as $role) {
            $hasViaRole = $role->permissions->contains(
                fn ($p) => $p->module === $module && $p->action === $action
            );
            if ($hasViaRole) {
                return true;
            }
        }

        return false;
    }
}
