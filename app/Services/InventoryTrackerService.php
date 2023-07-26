<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\Broadcastable;
use Throwable;

class InventoryTrackerService
{
    use Broadcastable;

    public function doChecks()
    {
        logger('Doing inventory checks');
        $this->checkLowStock();
    }

    public function checkLowStock()
    {
        try {
            // @phpstan-ignore-next-line
            $productsWithLowStock = Product::whereHas('stocks', function ($query) {
                $query->where('status', 'Available')
                    ->groupBy('product_id')
                    ->havingRaw('COUNT(*) <= 5');
            })->get();

            $count = $productsWithLowStock->count();

            if ($count > 0) {
                $this->notifyLowStock($count);
            } else {
                logger('No low stock');
            }
        } catch (Throwable $th) {
            logger($th->getMessage() . ' ' . $th->getFile() . ' ' . $th->getLine());
        }
    }
}
