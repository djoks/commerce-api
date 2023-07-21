<?php

namespace App\StateMachines;

use App\Services\StatusService;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class EquipmentTransferStatusStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return true;
    }

    public function transitions(): array
    {
        return (new StatusService())->getEquipmentTransferStatuses();
    }

    public function defaultState(): ?string
    {
        return 'Requested';
    }
}
