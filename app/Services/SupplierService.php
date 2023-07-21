<?php

namespace App\Services;

use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

class SupplierService extends BaseService
{
    protected $model = Supplier::class;

    protected $resource = SupplierResource::class;

    public function get()
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name,phone,email')
            ->paged();

        return $this->resource::collection($data);
    }
}
