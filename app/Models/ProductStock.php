<?php

namespace App\Models;

class ProductStock extends BaseModel
{
    protected $fillable = [
        'supplier_id',
        'product_id',
        'initial_quantity',
        'available_quantity',
        'purchase_date',
        'manufacture_date',
        'expiry_date',
        'notes',
    ];

    public static function scopeOfProduct($query, ?int $productId = null)
    {
        $field = request()->product_id ?? $productId;

        return $query->when(!is_null($field), fn ($query) => $query->where('product_id', $field));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
