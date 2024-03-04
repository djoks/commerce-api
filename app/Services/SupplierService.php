<?php

namespace App\Services;

use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

/**
 * Provides services for managing supplier entities.
 */
class SupplierService extends BaseService
{
    /**
     * @var string The model this service pertains to.
     */
    protected $model = Supplier::class;

    /**
     * @var string The resource class used for transforming supplier models into standardized API responses.
     */
    protected $resource = SupplierResource::class;

    /**
     * Retrieves a paginated list of suppliers, including search and filter capabilities.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of supplier records as a resource collection.
     */
    public function get()
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name,phone,email')
            ->paged();

        return $this->resource::collection($data);
    }
}
