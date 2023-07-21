<?php

namespace App\Http\Controllers\V1;

use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends BaseController
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->service->register((object) $request->all());

        return $this->apiResponse($response);
    }

    public function verify(VerifyRequest $request)
    {
        $response = $this->service->verify((object) $request->all());

        return $this->apiResponse($response);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->service->login((object) $request->validated());

        return $this->apiResponse($response);
    }

    public function logout()
    {
        $response = $this->service->logout();

        return $this->apiResponse($response);
    }
}
