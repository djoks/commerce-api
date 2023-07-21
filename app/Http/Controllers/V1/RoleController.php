<?php

namespace App\Http\Controllers\V1;

use App\Services\RoleService;

class RoleController extends BaseController
{
    protected $service;

    public function __construct(RoleService $service)
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
}
