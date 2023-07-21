<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\EquipmentCreateRequest;
use App\Http\Requests\EquipmentUpdateRequest;

class ProductController extends BaseController
{
    protected $service;

    public function __construct(ProductService $service)
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
    public function store(ProductRequest $request)
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
    public function update(ProductRequest $request, string $id)
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
