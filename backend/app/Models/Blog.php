<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Blog extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}
