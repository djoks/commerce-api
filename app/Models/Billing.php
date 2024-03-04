<?php

namespace App\Models;

class Billing extends BaseModel
{
    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'street_address',
        'city',
        'state',
        'country',
        'is_default'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
