<?php

namespace App\Services;

use App\Http\Resources\DiscountResource;
use App\Models\Discount;

/**
 * Provides services for managing discount records, extending common functionalities defined in BaseService.
 */
class DiscountService extends BaseService
{
    /**
     * @var string The model this service pertains to.
     */
    protected $model = Discount::class;

    /**
     * @var string The resource class used for transforming discount models into standardized API responses.
     */
    protected $resource = DiscountResource::class;

    /**
     * Retrieves a paginated list of discounts, optionally filtering by search criteria on the discount name.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of discount records as a resource collection.
     */
    public function get()
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name')
            ->paged();

        return $this->resource::collection($data);
    }
}
