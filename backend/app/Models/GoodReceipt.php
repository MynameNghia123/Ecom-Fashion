<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodReceipt extends Model
{
    protected $table = 'goods_receipts';

    protected $fillable = [
        'receipt_code',
        'supplier_id',
        'staff_id',
        'total_amount_price',
        'status',
    ];
    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'total_amount_price' => 'decimal:2',
        ];
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function goodReceiptDetail(): HasMany
    {
        return $this->hasMany(GoodReceiptDetail::class, 'goods_receipt_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
