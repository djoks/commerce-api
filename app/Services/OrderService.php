<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\Payable;
use Throwable;

class OrderService extends BaseService
{
    use Payable;

    protected $model = Order::class;

    protected $resource = OrderResource::class;

    protected $relationships = [
        'items',
        'items.equipment',
        'items.equipmentStock',
        'client',
        'payments',
        'lease',
    ];

    public function get(?int $branchId = null)
    {
        $data = $this->model::latest()
            ->filterByKeyword()
            ->with($this->relationships)
            ->ofBranch($branchId)
            ->ofStatus()
            ->paged();

        return $this->resource::collection($data);
    }

    public function checkPaymentStatus($invoiceId)
    {
        try {
            $invoice = $this->model::find($invoiceId);

            if ($invoice) {
                $this->checkStatus($invoice);
            }
        } catch (Throwable $th) {
            logger($th->getMessage());
        }
    }

    public function getMyOrders($for = 'today')
    {
        $data = $this->model::latest()
            ->with($this->relationships)
            ->whereCreatorId(auth()->id())
            ->ofStatus('Paid')
            ->filterByDateRange($for)
            ->paged();

        return $this->resource::collection($data);
    }
}
