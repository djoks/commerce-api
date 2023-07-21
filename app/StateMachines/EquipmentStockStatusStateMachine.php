<?php

namespace App\StateMachines;

use App\Services\StatusService;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class EquipmentStockStatusStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return true;
    }

    public function transitions(): array
    {
        return (new StatusService())->getEquipmentStockStatuses();
    }

    public function defaultState(): ?string
    {
        return 'Available';
    }
}
