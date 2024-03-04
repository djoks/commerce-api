<?php

namespace App\StateMachines;

use App\Services\StatusService;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class ProductStatusStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return true;
    }

    public function transitions(): array
    {
        return (new StatusService())->getProductStatuses();
    }

    public function defaultState(): ?string
    {
        return 'Available';
    }
}
