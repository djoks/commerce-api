<?php

namespace App\Services;

use App\Http\Resources\DiscountResource;
use App\Models\Discount;

class DiscountService extends BaseService
{
    protected $model = Discount::class;

    protected $resource = DiscountResource::class;

    public function get()
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name')
            ->paged();

        return $this->resource::collection($data);
    }
}
