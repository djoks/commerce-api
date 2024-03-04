<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

/**
 * Manages checkout operations within the e-commerce API, including initiating a checkout process and verifying checkout via OTP.
 */
class CheckoutController extends BaseController
{
    /**
     * @var CheckoutService Holds the service instance for checkout operations.
     */
    protected CheckoutService $service;

    /**
     * Constructor for the CheckoutController.
     * 
     * @param CheckoutService $service The checkout service dependency.
     */
    public function __construct(CheckoutService $service)
    {
        $this->service = $service;
    }

    /**
     * Processes a checkout operation based on the provided checkout details.
     * 
     * @param CheckoutRequest $request The request object containing the necessary details for checkout.
     * @return \Illuminate\Http\Response Returns the API response with the result of the checkout operation.
     */
    public function checkout(CheckoutRequest $request)
    {
        $response = $this->service->checkout((object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Validates an OTP for a checkout operation, identified by the invoice ID.
     * 
     * @param string $invoiceId The unique identifier for the invoice associated with the checkout.
     * @param Request $request The request object containing the OTP to be validated.
     * @return \Illuminate\Http\Response Returns the API response with the result of the OTP validation.
     */
    public function checkoutOtp(string $invoiceId, Request $request)
    {
        $response = $this->service->checkoutOtp($invoiceId, (object) $request->validated());

        return $this->apiResponse($response);
    }
}
