<?php

namespace App\Services;

use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryService extends BaseService
{
    protected $model = Category::class;

    protected $resource = CategoryResource::class;

    public function get()
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name')
            ->paged();

        return $this->resource::collection($data);
    }
}
