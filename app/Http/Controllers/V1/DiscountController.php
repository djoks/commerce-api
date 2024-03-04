<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\DiscountRequest;
use App\Services\DiscountService;

/**
 * Manages discount operations for the e-commerce API, including listing, creating, viewing, updating, and deleting discounts.
 */
class DiscountController extends BaseController
{
    /**
     * @var DiscountService Holds the service instance for managing discount operations.
     */
    protected $service;

    /**
     * Constructor for the DiscountController.
     * 
     * @param DiscountService $service The discount service dependency.
     */
    public function __construct(DiscountService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves a list of all discounts.
     * 
     * @return \Illuminate\Http\Response Returns the API response with the list of discounts.
     */
    public function index()
    {
        return $this->service->get();
    }

    /**
     * Creates a new discount record with the provided details.
     * 
     * @param DiscountRequest $request The request object containing the details for the new discount.
     * @return \Illuminate\Http\Response Returns the API response with the result of the discount creation.
     */
    public function store(DiscountRequest $request)
    {
        $response = $this->service->create((object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Displays the details of a specific discount.
     * 
     * @param string $id The unique identifier of the discount to be displayed.
     * @return \Illuminate\Http\Response Returns the API response with the details of the specified discount.
     */
    public function show(string $id)
    {
        $response = $this->service->findOne($id);

        return $this->apiResponse($response);
    }

    /**
     * Updates the details of an existing discount.
     * 
     * @param DiscountRequest $request The request object containing the updated details for the discount.
     * @param string $id The unique identifier of the discount to be updated.
     * @return \Illuminate\Http\Response Returns the API response with the result of the discount update.
     */
    public function update(DiscountRequest $request, string $id)
    {
        $response = $this->service->update($id, (object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Deletes a specific discount.
     * 
     * @param string $id The unique identifier of the discount to be deleted.
     * @return \Illuminate\Http\Response Returns the API response with the result of the discount deletion.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        return $this->apiResponse($response);
    }
}
