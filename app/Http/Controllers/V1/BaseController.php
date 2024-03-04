<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

/**
 * Base controller class that other API controllers extend from. 
 * Utilizes the ApiResponser trait for standardized API responses.
 */
class BaseController extends Controller
{
    use ApiResponser;

    /**
     * @var mixed A placeholder for service dependency, intended for use in derived classes.
     */
    protected $service;
}
