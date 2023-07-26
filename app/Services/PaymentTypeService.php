<?php

namespace App\Services;

use App\Models\PaymentType;
use App\Http\Resources\PaymentTypeResource;

class PaymentTypeService extends BaseService
{
    protected $model = PaymentType::class;

    protected $resource = PaymentTypeResource::class;

    public function get()
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name')
            ->paged();

        return $this->resource::collection($data);
    }
}
