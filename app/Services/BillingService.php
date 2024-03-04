<?php

namespace App\Services;

use App\Models\Billing;
use App\Models\ApiResponse;
use App\Http\Resources\BillingResource;

/**
 * Provides billing-related services, extending the common functionalities defined in BaseService.
 * Manages operations specific to billing entities, such as retrieving billing information for the authenticated user.
 */
class BillingService extends BaseService
{
    /**
     * @var string The model this service pertains to.
     */
    protected $model = Billing::class;

    /**
     * @var string The model this service pertains to.
     */
    protected $resource = BillingResource::class;

    /**
     * Retrieves a paginated list of billing records for the authenticated user, 
     * optionally filtering by search criteria on street address, city, and country.
     *
     * Overrides the generic get method in BaseService to apply specific logic for billing entities.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of billing records as a resource collection.
     */
    public function get()
    {
        $data = $this->model::orderBy('created_at', 'desc')
            ->where('customer_id', auth()->id())
            ->search(fieldNames: 'street_address,city,country')
            ->paged();

        return $this->resource::collection($data);
    }
}
