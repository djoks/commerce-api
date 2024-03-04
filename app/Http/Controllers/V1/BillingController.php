<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\BillingRequest;
use App\Services\BillingService;
use Illuminate\Http\Request;

/**
 * Manages billing operations within the e-commerce API, including listing, creating, 
 * updating, and deleting billing information.
 */
class BillingController extends BaseController
{
    /**
     * @var BillingService The service handling business logic for billing operations.
     */
    protected BillingService $service;

    /**
     * Initializes a new instance of the BillingController class with the necessary service.
     * 
     * @param BillingService $service The billing service dependency.
     */
    public function __construct(BillingService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves a list of billing records.
     * 
     * @param Request $request The incoming request instance.
     * @return \Illuminate\Http\Response Returns a list of billing records.
     */
    public function index(Request $request)
    {
        return $this->service->get();
    }

    /**
     * Creates a new billing record.
     * 
     * @param BillingRequest $request The request object containing billing details.
     * @return \Illuminate\Http\Response Returns the response after creating a billing record.
     */
    public function store(BillingRequest $request)
    {
        $validated = $request->validated();
        $validated['customer_id'] = auth()->id();
        $response = $this->service->create((object) $validated);

        return $this->apiResponse($response);
    }

    /**
     * Displays a specific billing record.
     * 
     * @param string $id The unique identifier of the billing record.
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $response = $this->service->findOne($id);

        return $this->apiResponse($response);
    }

    /**
     * Updates an existing billing record.
     * 
     * @param BillingRequest $request The request object containing updated billing details.
     * @param string $id The unique identifier of the billing record to update.
     * @return \Illuminate\Http\Response
     */
    public function update(BillingRequest $request, string $id)
    {
        $response = $this->service->update($id, (object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Deletes a specific billing record.
     * 
     * @param string $id The unique identifier of the billing record to delete.
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        return $this->apiResponse($response);
    }
}
