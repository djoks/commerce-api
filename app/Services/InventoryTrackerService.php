<?php

namespace App\Services;

use App\Models\Equipment;
use App\Models\Lease;
use App\Traits\Notifiable;
use Throwable;

class InventoryTrackerService
{
    use Notifiable;

    public function doChecks()
    {
        logger('Doing inventory checks');

        $this->checkLowStock();
        $this->checkOverDueLease();
    }

    public function checkLowStock()
    {
        try {
            // @phpstan-ignore-next-line
            $equipmentWithLowStock = Equipment::whereHas('stocks', function ($query) {
                $query->where('status', 'Available')
                    ->groupBy('equipment_id')
                    ->havingRaw('COUNT(*) <= 5');
            })->get();

            $count = $equipmentWithLowStock->count();
            if ($count > 0) {
                $this->notifyLowStock($count);
            } else {
                logger('No low stock');
            }
        } catch (Throwable $th) {
            logger($th->getMessage() . ' ' . $th->getFile() . ' ' . $th->getLine());
        }
    }

    public function checkOverDueLease()
    {
        try {
            $overDueLease = Lease::where('ends_at', '<', now()->toDateString());
            $count = $overDueLease->count();

            if ($count > 0) {
                $this->notifyOverDueLease($count);
            } else {
                logger('No overdue lease');
            }
        } catch (Throwable $th) {
            logger($th->getMessage() . ' ' . $th->getFile() . ' ' . $th->getLine());
        }
    }
}
