<?php

namespace App\Models;

class Supplier extends BaseModel
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
    ];
}
