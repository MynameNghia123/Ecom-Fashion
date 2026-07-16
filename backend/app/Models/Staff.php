<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

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

    protected $casts = [
        'is_active' => 'int',
        'password'  => 'hashed',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
