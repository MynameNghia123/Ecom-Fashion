<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reason',
        'evidence_images',
        'status',
        'refund_amount',
        'processed_by_staff_id',
    ];

    protected $casts = [
        'evidence_images' => 'array',
        'refund_amount'   => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function processedByStaff()
    {
        return $this->belongsTo(Staff::class, 'processed_by_staff_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
