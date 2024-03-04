<?php

namespace App\Services;

use App\Models\Category;
use App\Http\Resources\CategoryResource;

/**
 * Provides category-related services, extending the common functionalities defined in BaseService.
 * Manages operations specific to category entities, such as retrieving a paginated list of categories.
 */
class CategoryService extends BaseService
{
    /**
     * The model this service pertains to.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * The resource class used for transforming category models into standardized API responses.
     *
     * @var string
     */
    protected $resource = CategoryResource::class;

    /**
     * Retrieves a paginated list of categories, optionally filtering by search criteria on the category name.
     *
     * Overrides the generic get method in BaseService to apply specific logic for category entities.
     * Categories are ordered alphabetically by their name.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of category records as a resource collection.
     */
    public function get()
    {
        $data = $this->model::orderBy('name', 'asc')
            ->search(fieldNames: 'name')
            ->paged();

        return $this->resource::collection($data);
    }
}
