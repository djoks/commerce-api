<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\SupplierCreateRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Services\SupplierService;

/**
 * Handles supplier-related operations within the e-commerce API.
 * Provides functionality for listing all suppliers, creating new suppliers,
 * viewing specific suppliers, updating, and deleting suppliers.
 */
class SupplierController extends BaseController
{
    /**
     * @var SupplierService Holds the service instance for managing supplier operations.
     */
    protected $service;

    /**
     * Initializes a new instance of the SupplierController class.
     *
     * @param SupplierService $service Injected service for managing suppliers.
     */
    public function __construct(SupplierService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves a list of all suppliers.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * Returns the API response with a list of all suppliers.
     */
    public function index()
    {
        return $this->service->get();
    }

    /**
     * Creates a new supplier with the provided details.
     *
     * @param SupplierCreateRequest $request The request object containing the details for the new supplier.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the supplier creation.
     */
    public function store(SupplierCreateRequest $request)
    {
        $response = $this->service->create((object) $request->all());

        return $this->apiResponse($response);
    }

    /**
     * Displays the details of a specific supplier identified by ID.
     *
     * @param string $id The unique identifier of the supplier to display.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the details of the specified supplier.
     */
    public function show(string $id)
    {
        $response = $this->service->findOne($id);

        return $this->apiResponse($response);
    }

    /**
     * Updates the details of an existing supplier.
     *
     * @param SupplierUpdateRequest $request The request object containing the updated details for the supplier.
     * @param string $id The unique identifier of the supplier to be updated.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the supplier update.
     */
    public function update(SupplierUpdateRequest $request, string $id)
    {
        $response = $this->service->update($id, (object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Removes a specific supplier from the system.
     *
     * @param string $id The unique identifier of the supplier to delete.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the supplier deletion.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        return $this->apiResponse($response);
    }
}
