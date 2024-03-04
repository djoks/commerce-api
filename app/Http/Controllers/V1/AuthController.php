<?php

namespace App\Http\Controllers\V1;

use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Requests\RegisterRequest;

/**
 * Handles authentication processes such as registration, verification, login, and logout.
 */
class AuthController extends BaseController
{
    /**
     * @var AuthService The authentication service instance.
     */
    protected $service;

    /**
     * Create a new AuthController instance.
     *
     * @param AuthService $service The authentication service dependency.
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Registers a new user.
     *
     * @param RegisterRequest $request The registration request with user details.
     * @return \Illuminate\Http\JsonResponse A standardized API response with registration outcome.
     */
    public function register(RegisterRequest $request)
    {
        $response = $this->service->register((object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Verifies a user's email.
     *
     * @param VerifyRequest $request The verification request with necessary parameters.
     * @return \Illuminate\Http\JsonResponse A standardized API response with verification outcome.
     */
    public function verify(VerifyRequest $request)
    {
        $response = $this->service->verify((object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Logs in a user.
     *
     * @param LoginRequest $request The login request with credentials.
     * @return \Illuminate\Http\JsonResponse A standardized API response with login outcome.
     */
    public function login(LoginRequest $request)
    {
        $response = $this->service->login((object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Logs out the current user.
     *
     * @return \Illuminate\Http\JsonResponse A standardized API response with logout outcome.
     */
    public function logout()
    {
        $response = $this->service->logout();

        return $this->apiResponse($response);
    }
}
