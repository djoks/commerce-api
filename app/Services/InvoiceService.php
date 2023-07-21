<?php

namespace App\Services;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Traits\Payable;
use Throwable;

class InvoiceService extends BaseService
{
    use Payable;

    protected $model = Invoice::class;

    protected $resource = InvoiceResource::class;

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

    public function checkPaymentsStatus($invoiceId)
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

    public function getMySales($for = 'today')
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
