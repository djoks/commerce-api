<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\ClientCreateRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Services\ClientService;
use Illuminate\Http\Request;

class BillingController extends BaseController
{
    protected $service;

    public function __construct(ClientService $service)
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
     * Store a newly created resource in storage.
     */
    public function store(ClientCreateRequest $request)
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
    public function update(ClientUpdateRequest $request, string $id)
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
