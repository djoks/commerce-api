<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;

class CategoryController extends BaseController
{
    protected $service;

    public function __construct(CategoryService $service)
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
    public function store(CategoryRequest $request)
    {
        $response = $this->service->create((object) $request->all());

        return $this->apiResponse($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $response = $this->service->findOne($slug);

        return $this->apiResponse($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $slug)
    {
        $response = $this->service->update($slug, (object) $request->all());

        return $this->apiResponse($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $response = $this->service->delete($slug);

        return $this->apiResponse($response);
    }
}
