<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;

/**
 * Handles category operations for the e-commerce API, including listing, creating, viewing, updating, and deleting categories.
 */
class CategoryController extends BaseController
{
    /**
     * @var CategoryService Holds the service instance for category operations.
     */
    protected $service;

    /**
     * Constructor for the CategoryController.
     * 
     * @param CategoryService $service The category service dependency.
     */
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves a list of all categories.
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return $this->service->get();
    }

    /**
     * Creates a new category with the given details.
     * 
     * @param CategoryRequest $request The request object containing category details.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $response = $this->service->create((object) $request->all());

        return $this->apiResponse($response);
    }

    /**
     * Displays the specified category by slug.
     * 
     * @param string $slug The slug identifier of the category to display.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        $response = $this->service->findOne(slug: $slug);

        return $this->apiResponse($response);
    }

    /**
     * Updates the specified category with new details.
     * 
     * @param CategoryRequest $request The request object containing updated category details.
     * @param string $id The unique identifier of the category to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, string $id)
    {
        $response = $this->service->update($id, (object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Deletes the specified category by slug.
     * 
     * @param string $slug The slug identifier of the category to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $slug)
    {
        $response = $this->service->delete($slug);

        return $this->apiResponse($response);
    }
}
