<?php

namespace App\Services;

use App\Models\PaymentType;
use App\Http\Resources\PaymentTypeResource;

/**
 * Provides services related to payment type management.
 */
class PaymentTypeService extends BaseService
{
    /**
     * @var string The model this service pertains to.
     */
    protected $model = PaymentType::class;

    /**
     * @var string The resource class used for transforming payment type models into standardized API responses.
     */
    protected $resource = PaymentTypeResource::class;

    /**
     * Retrieves a paginated list of payment types, optionally filtering by the payment type name.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of payment type records as a resource collection.
     */
    public function get()
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name')
            ->paged();

        return $this->resource::collection($data);
    }
}
