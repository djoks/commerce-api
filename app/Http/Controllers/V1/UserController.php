<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserRoleRequest;
use App\Http\Requests\UserStatusRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected $service;

    public function __construct(UserService $service)
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
    public function store(UserCreateRequest $request)
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
    public function update(UserUpdateRequest $request, string $id)
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

    public function updateStatus(UserStatusRequest $request, string $id)
    {
        $response = $this->service->changeActive($id, $request->active);

        return $this->apiResponse($response);
    }

    public function updateRole(UserRoleRequest $request, string $id)
    {
        $response = $this->service->updateRole($id, $request->role);

        return $this->apiResponse($response);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $response = $this->service->updatePassword(auth()->id(), (object) $request->validated());

        return $this->apiResponse($response);
    }
}
