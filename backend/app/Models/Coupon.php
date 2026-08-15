<?php

namespace App\Models;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'discount_value',
        'price_min_order_value',
        'max_usage',
        'used_count',
        'is_active',
        'expiry_date',
        'created_by_staff_id',
    ];

    protected $casts = [
        'type' => CouponType::class,
        'is_active' => 'boolean',
        'discount_value' => 'float',
        'price_min_order_value' => 'float',
        'max_usage' => 'integer',
        'used_count' => 'integer',
        'expiry_date' => 'date:Y-m-d',
    ];

    public function createdByStaff()
    {
        return $this->belongsTo(Staff::class, 'created_by_staff_id');
    }
}
