<?php

namespace App\Http\Controllers\V1;

use App\Services\InvoiceService;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    protected $service;

    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->service->get($request->_branch_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Check Payments status
        $this->service->checkPaymentsStatus($id);

        $response = $this->service->findOne($id);

        return $this->apiResponse($response);
    }

    public function mySales()
    {
        return $this->service->getMySales(request()->for);
    }
}
