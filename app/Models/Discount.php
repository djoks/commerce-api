<?php

namespace App\Models;

class Discount extends BaseModel
{
    protected $fillable = [
        'name',
        'value',
        'max_value',
        'unit',
        'notes',
        'min_device_count',
        'max_device_count',
    ];

    protected $casts = [
        'value' => 'float',
        'max_value' => 'float',
        'min_device_count' => 'integer',
        'max_device_count' => 'integer',
    ];

    public function applyDiscount(float $total): float
    {
        $discounted = 0;

        if ($this->unit === 'percentage') {
            $discountAmount = $total * $this->value / 100;
            $max = $this->max_value;

            if ($max > 0 && $discountAmount > $max) {
                $discounted = $total - $max;
            } else {
                $discounted = $total - $discountAmount;
            }
        } else {
            $discounted = $total - $this->value;
        }

        return $discounted < 0 ? 0 : $discounted;
    }
}
