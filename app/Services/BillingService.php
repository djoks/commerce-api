<?php

namespace App\Services;

use App\Http\Resources\ClientResource;
use App\Models\Client;

class BillingService extends BaseService
{
    protected $model = Client::class;

    protected $resource = ClientResource::class;

    public function get(?int $branchId = null)
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name,phone,email')
            ->paged();

        return $this->resource::collection($data);
    }
}
