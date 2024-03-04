<?php

namespace App\Http\Controllers\V1;

use App\Services\RoleService;

/**
 * Manages role operations within the e-commerce API, specifically listing all available roles.
 */
class RoleController extends BaseController
{
    /**
     * @var RoleService Holds the service instance for managing role operations.
     */
    protected RoleService $service;

    /**
     * Initializes a new instance of the RoleController class.
     *
     * @param RoleService $service The injected service for managing roles.
     */
    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves a list of all roles.
     *
     * Utilizes the RoleService to fetch and return all roles currently defined within the system. 
     * This method is responsible for providing an overview of all roles, aiding in role management and assignment tasks.
     *
     * @return \Illuminate\Http\Response Returns the API response with a list of roles.
     */
    public function index()
    {
        return $this->service->get();
    }
}
