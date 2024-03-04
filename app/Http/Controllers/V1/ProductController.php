<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\EquipmentCreateRequest;
use App\Http\Requests\EquipmentUpdateRequest;

/**
 * Manages product operations within the e-commerce API, including listing, creating,
 * viewing, updating, and deleting products.
 */
class ProductController extends BaseController
{
    /**
     * @var ProductService Holds the service instance for managing product operations.
     */
    protected $service;

    /**
     * Initializes a new instance of the ProductController class.
     *
     * @param ProductService $service Injected service for managing products.
     */
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves a list of all products.
     *
     * @param Request $request The request object, potentially containing filters and pagination.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * Returns the API response with a list of products.
     */
    public function index(Request $request)
    {
        return $this->service->get();
    }

    /**
     * Creates a new product with the provided details.
     *
     * @param ProductRequest $request The request object containing the details for the new product.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the product creation.
     */
    public function store(ProductRequest $request)
    {
        $response = $this->service->create((object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Displays the details of a specific product identified by slug.
     *
     * @param string $slug The slug identifier of the product.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the details of the specified product.
     */
    public function show(string $slug)
    {
        $response = $this->service->findOne(slug: $slug);

        return $this->apiResponse($response);
    }

    /**
     * Updates the details of an existing product.
     *
     * @param ProductRequest $request The request object containing the updated details for the product.
     * @param string $id The unique identifier of the product to be updated.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the product update.
     */
    public function update(ProductRequest $request, string $id)
    {
        $response = $this->service->update($id, (object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Deletes a specific product.
     *
     * @param string $id The unique identifier of the product to be deleted.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the product deletion.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        return $this->apiResponse($response);
    }
}
