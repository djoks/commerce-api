<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\PaymentTypeRequest;
use App\Services\PaymentTypeService;

class PaymentTypeController extends BaseController
{
    protected $service;

    public function __construct(PaymentTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->service->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentTypeRequest $request)
    {
        $response = $this->service->create((object) $request->all());

        return $this->apiResponse($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->service->findOne($id);

        return $this->apiResponse($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentTypeRequest $request, string $id)
    {
        $response = $this->service->update($id, (object) $request->all());

        return $this->apiResponse($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        return $this->apiResponse($response);
    }
}
