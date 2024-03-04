<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductService extends BaseService
{
    protected $model = Product::class;

    protected $resource = ProductResource::class;

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
