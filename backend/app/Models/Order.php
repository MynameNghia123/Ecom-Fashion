<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'customer_id',
        'coupon_id',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'sub_total_amount',
        'coupon_discount_amount',
        'shipping_fee',
        'final_amount',
        'status',
        'payment_method',
        'payment_status',
        'transaction_id',
    ];

    protected $casts = [
        'sub_total_amount' => 'decimal:2',
        'coupon_discount_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
