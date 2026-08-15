<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    protected $fillable = [
        'ticket_code', 'order_id', 'order_detail_id',
        'reason', 'customer_note', 'evidence_images',
        'quantity', 'refund_amount', 'status',
        'admin_note', 'processed_by_staff_id', 'processed_at',
    ];

    protected $casts = [
        'evidence_images' => 'array',
        'processed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderDetail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'processed_by_staff_id');
    }
}
