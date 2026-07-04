<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Banner extends Model
{
    protected $fillable = [
        'title',
        'image_url',
        'target_url',
        'position',
        'display_order',
        'is_active',
        'start_date',
        'end_date',
    ];
    protected function casts(): array
    {
        return [
           'is_active' => 'boolean',
           'display_order' => 'integer',
           'start_date' => 'datetime',
           'end_date' => 'datetime'
        ];
    }
}
