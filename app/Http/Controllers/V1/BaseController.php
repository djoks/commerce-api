<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class BaseController extends Controller
{
    use ApiResponser;

    protected $service;
}
