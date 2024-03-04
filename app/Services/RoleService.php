<?php

namespace App\Services;

use App\Http\Resources\RoleResource;
use App\Models\ApiResponse;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Throwable;

/**
 * Provides services related to role management, including retrieving roles for all users or a specific user.
 */
class RoleService extends BaseService
{
    /**
     * @var string The model this service pertains to.
     */
    protected $model = Role::class;

    /**
     * @var string The resource class used for transforming role models into standardized API responses.
     */
    protected $resource = RoleResource::class;

    /**
     * Retrieves roles, optionally for a specific user based on a user ID.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\App\Models\ApiResponse Returns a collection of roles or an ApiResponse on failure.
     */
    public function get()
    {
        $userId = request('user_id');
        if ($userId) {
            return $this->getUserRole($userId);
        }

        $data = $this->model::orderBy('name', 'asc')->with('permissions')->get();

        return $this->resource::collection($data);
    }

    /**
     * Retrieves roles for a specific user.
     *
     * @param mixed $userId The ID of the user whose roles are to be retrieved.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\App\Models\ApiResponse Returns a collection of roles for the specified user or an ApiResponse on failure.
     */
    public function getUserRole($userId)
    {
        try {
            $user = User::find($userId)->load('roles');

            return $this->resource::collection($user->roles);
        } catch (Throwable $e) {
            return new ApiResponse('Sorry, could not fetch user roles', 500);
        }
    }
}
