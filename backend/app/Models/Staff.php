<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Staff extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable;

    protected $table = 'staffs';

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone_number',
        'avatar',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active'     => 'int',
        'password'      => 'hashed',
        'last_login_at' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    // ── JWTSubject interface ──────────────────────────────────────────────────
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'email'     => $this->email,
            'full_name' => $this->full_name,
            'is_active' => $this->is_active,
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────
    public function StaffPermissions(): HasMany
    {
        return $this->hasMany(StaffPermissions::class);
    }

    public function StaffRoles(): HasMany
    {
        return $this->hasMany(StaffRoles::class);
    }
}
