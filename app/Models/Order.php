<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $fillable = [
        'user_id',
        'code',
        'sub_total',
        'tax',
        'discount',
        'total',
        'shipping',
        'meta',
    ];

    protected $casts = [
        'meta' => 'json',
        'shipping' => 'json',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeOfStatus($query, ?string $status = null)
    {
        $field = request()->status ?? $status;

        return $query->when(!is_null($field), function ($query) use ($field) {
            return $query->whereHas('payments', function ($query) use ($field) {
                $query->where('status', $field);
            });
        });
    }

    public function scopeFilterByKeyword($query)
    {
        $field = request()->keyword;

        return $query->when(!is_null($field), function ($query) use ($field) {
            return $query->where('code', 'like', "%{$field}%")
                ->orWhereHas('user', function ($query) use ($field) {
                    $query->orWhere('email', 'like', "%{$field}%")
                        ->orWhere('phone', 'like', "%{$field}%");
                });
        });
    }

    public function scopeFilterByDateRange($query, $for = 'today')
    {
        $for = request()->for ?? $for;
        $dateRange = $this->getDateRange($for);

        return $query->whereBetween('created_at', $dateRange);
    }

    protected function getDateRange($for)
    {
        $endDate = today();

        switch ($for) {
            case 'today':
                $startDate = $endDate->copy();
                break;
            case 'since_yesterday':
                $startDate = $endDate->copy()->subDay();
                break;
            case 'past_3_days':
                $startDate = $endDate->copy()->subDays(2);
                break;
            case 'past_7_days':
                $startDate = $endDate->copy()->subDays(6);
                break;
            case 'past_30_days':
                $startDate = $endDate->copy()->subDays(29);
                break;
            default:
                $startDate = $endDate->copy();
                break;
        }

        $endDate = $endDate->copy()->addDay();

        return [$startDate->toDateString(), $endDate->toDateString()];
    }
}
