<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodReceiptDetail extends Model
{
    protected $table = 'goods_receipt_details';

    protected $fillable = [
        'goods_receipt_id',
        'product_variant_id',
        'quantity',
        'import_price',
    ];

    protected function casts(): array
    {
        return [
            'goods_receipt_id'   => 'integer',
            'product_variant_id' => 'integer',
            'quantity'           => 'integer',
            'import_price'       => 'integer', 
        ];
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
    
    public function goodReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodReceipt::class, 'goods_receipt_id');
    }
}
