<?php

namespace App\Models;

use App\StateMachines\PaymentStatusStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;

class Payment extends BaseModel
{
    use HasStateMachines;

    protected $fillable = [
        'invoice_id',
        'transaction_id',
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

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
