<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffRoles extends Model
{
    protected $fillable = [
        'staff_id',
        'role_id',
    ];

    public function Staff() : BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function Role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
