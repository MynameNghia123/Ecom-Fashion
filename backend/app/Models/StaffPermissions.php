<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffPermissions extends Model
{
    protected $fillable = [
        'staff_id',
        'permission_id'
    ];

    public function Staff() : BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function Permission() : BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
