<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'brand',
        'thumbnail',
        'user_manual',
        'is_active',
        'created_by_staff_id',
        'updated_by_staff_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
?>