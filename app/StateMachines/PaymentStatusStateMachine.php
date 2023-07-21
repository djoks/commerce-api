<?php

namespace App\StateMachines;

use App\Services\StatusService;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class PaymentStatusStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return true;
    }

    public function transitions(): array
    {
        return (new StatusService())->getPaymentStatuses();
    }

    public function defaultState(): ?string
    {
        return 'Pending';
    }
}
