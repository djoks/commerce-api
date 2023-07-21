<?php

namespace App\Models;

class OrderItem extends BaseModel
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'price',
        'quantity',
        'amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
