<?php

namespace App\Models;

class Billing extends BaseModel
{
    protected $fillable = [
        'user_id',
        'payment_id',
        'first_name',
        'last_name',
        'street_address',
        'city',
        'state',
        'country'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
