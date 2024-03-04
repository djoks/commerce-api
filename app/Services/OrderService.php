<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\Payable;
use Throwable;

/**
 * Provides services related to order management, extending the common functionalities defined in BaseService.
 * Includes methods for retrieving orders, checking payment status, and retrieving orders for the authenticated user.
 */
class OrderService extends BaseService
{
    use Payable;

    /**
     * @var string The model this service pertains to.
     */
    protected $model = Order::class;

    /**
     * @var string The resource class used for transforming order models into standardized API responses.
     */
    protected $resource = OrderResource::class;

    /**
     * @var array The relationships that should be loaded with order queries.
     */
    protected $relationships = [
        'items',
        'items.product',
        'customer',
        'payment'
    ];

    /**
     * Retrieves a paginated list of orders, optionally applying filters and including specified relationships.
     *
     * @param int|null $branchId Optional branch ID to filter orders.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of order records as a resource collection.
     */
    public function get(?int $branchId = null)
    {
        $data = $this->model::latest()
            ->filterByKeyword()
            ->with($this->relationships)
            ->ofStatus()
            ->paged();

        return $this->resource::collection($data);
    }

    /**
     * Checks and updates the payment status of a specific order by its invoice ID.
     *
     * @param mixed $invoiceId The ID of the invoice to check payment status for.
     * @return void Logs information or errors encountered during the payment status check.
     */
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

    /**
     * Retrieves a paginated list of orders for the authenticated user, filtered by the provided criteria (e.g., 'today').
     *
     * @param string $for The criteria to filter orders for the authenticated user.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of the authenticated user's order records as a resource collection.
     */
    public function getMyOrders($for = 'today')
    {
        $data = $this->model::latest()
            ->with($this->relationships)
            ->whereCustomerId(auth()->id())
            ->ofStatus('Paid')
            ->filterByDateRange($for)
            ->paged();

        return $this->resource::collection($data);
    }
}
