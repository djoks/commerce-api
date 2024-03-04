<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\PaymentTypeRequest;
use App\Services\PaymentTypeService;

/**
 * Manages payment type operations within the e-commerce API.
 * This includes handling the listing, creation, viewing, updating, and deletion of payment types.
 */
class PaymentTypeController extends BaseController
{
    /**
     * The service responsible for handling payment type operations.
     *
     * @var PaymentTypeService
     */
    protected $service;

    /**
     * Initializes a new instance of the PaymentTypeController class.
     *
     * @param PaymentTypeService $service The service for managing payment types, injected as a dependency.
     */
    public function __construct(PaymentTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves and displays a list of all payment types.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * Returns the API response containing a list of payment types.
     */
    public function index()
    {
        return $this->service->get();
    }

    /**
     * Creates a new payment type with the given details from the request.
     *
     * @param PaymentTypeRequest $request The request containing the details for the new payment type.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the creation operation.
     */
    public function store(PaymentTypeRequest $request)
    {
        $response = $this->service->create((object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Displays the details of a specific payment type, identified by its unique ID.
     *
     * @param string $id The unique identifier of the payment type to be displayed.
     * @return \Illuminate\Http\JsonResponse Returns the API response containing details of the specified payment type.
     */
    public function show(string $id)
    {
        $response = $this->service->findOne($id);

        return $this->apiResponse($response);
    }

    /**
     * Updates an existing payment type with new details provided in the request.
     *
     * @param PaymentTypeRequest $request The request containing updated details for the payment type.
     * @param string $id The unique identifier of the payment type to be updated.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the update operation.
     */
    public function update(PaymentTypeRequest $request, string $id)
    {
        $response = $this->service->update($id, (object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Deletes a specific payment type, identified by its unique ID.
     *
     * @param string $id The unique identifier of the payment type to be deleted.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the deletion operation.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        return $this->apiResponse($response);
    }
}
