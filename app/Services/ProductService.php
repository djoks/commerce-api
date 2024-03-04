<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;

/**
 * Provides services related to product management.
 */
class ProductService extends BaseService
{
    /**
     * @var string The model this service pertains to.
     */
    protected $model = Product::class;

    /**
     * @var string The resource class used for transforming product models into standardized API responses.
     */
    protected $resource = ProductResource::class;

    /**
     * Retrieves a paginated list of products, including relationships and applying filters and search criteria.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of product records as a resource collection.
     */
    public function get()
    {
        $data = $this->model::with(['category', 'media'])
            ->withAvailableStock()
            ->withPreferredOrder()
            ->search(fieldNames: 'name')
            ->filterByName()
            ->paged();

        return $this->resource::collection($data);
    }
}
