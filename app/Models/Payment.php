<?php

namespace App\Models;

use App\Models\Order;
use App\StateMachines\PaymentStatusStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;

class Payment extends BaseModel
{
    use HasStateMachines;

    protected $fillable = [
        'invoice_id',
        'reference',
        'type',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $stateMachines = [
        'status' => PaymentStatusStateMachine::class,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
