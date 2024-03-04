<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserRoleRequest;
use App\Http\Requests\UserStatusRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * Manages user operations within the e-commerce API, including listing,
 * creating, viewing, updating, and deleting users, as well as updating user statuses, roles, and passwords.
 */
class UserController extends BaseController
{
    /**
     * @var UserService Holds the service instance for managing user operations.
     */
    protected $service;

    /**
     * Initializes a new instance of the UserController class.
     *
     * @param UserService $service The injected service for managing users.
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves a list of all users.
     *
     * @param Request $request The request object, potentially containing filters and pagination.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * Returns the API response with a list of users.
     */
    public function index(Request $request)
    {
        return $this->service->get();
    }

    /**
     * Creates a new user with the provided details.
     *
     * @param UserRequest $request The request object containing the details for the new user.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the user creation.
     */
    public function store(UserRequest $request)
    {
        $response = $this->service->create((object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Displays the details of a specific user.
     *
     * @param string $id The unique identifier of the user.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the details of the specified user.
     */
    public function show(string $id)
    {
        $response = $this->service->findOne($id);

        return $this->apiResponse($response);
    }

    /**
     * Updates the specified user with new details.
     *
     * @param UserRequest $request The request object containing updated user details.
     * @param string $id The unique identifier of the user to update.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the user update.
     */
    public function update(UserRequest $request, string $id)
    {
        $response = $this->service->update($id, (object) $request->validated());

        return $this->apiResponse($response);
    }

    /**
     * Removes a specific user from the system.
     *
     * @param string $id The unique identifier of the user to delete.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the user deletion.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        return $this->apiResponse($response);
    }

    /**
     * Updates the status of a specific user.
     *
     * @param UserStatusRequest $request The request object containing the new status.
     * @param string $id The unique identifier of the user whose status is to be updated.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the status update.
     */
    public function updateStatus(UserStatusRequest $request, string $id)
    {
        $response = $this->service->changeActive($id, $request->active);

        return $this->apiResponse($response);
    }

    /**
     * Updates the role of a specific user.
     *
     * @param UserRoleRequest $request The request object containing the new role.
     * @param string $id The unique identifier of the user whose role is to be updated.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the role update.
     */
    public function updateRole(UserRoleRequest $request, string $id)
    {
        $response = $this->service->updateRole($id, $request->role);

        return $this->apiResponse($response);
    }

    /**
     * Updates the password for the currently authenticated user.
     *
     * @param UpdatePasswordRequest $request The request object containing the new password details.
     * @return \Illuminate\Http\JsonResponse Returns the API response with the result of the password update.
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $response = $this->service->updatePassword(auth()->id(), (object) $request->validated());

        return $this->apiResponse($response);
    }
}
