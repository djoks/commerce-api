<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends BaseController
{
    protected $service;

    public function __construct(CheckoutService $service)
    {
        $this->service = $service;
    }

    public function checkout(CheckoutRequest $request)
    {
        $response = $this->service->checkout((object) $request->all());

        return $this->apiResponse($response);
    }

    public function checkoutOtp(string $invoiceId, Request $request)
    {
        $response = $this->service->checkoutOtp($invoiceId, (object) $request->all());

        return $this->apiResponse($response);
    }
}
