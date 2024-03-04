<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\ApiResponse;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * Provides services for managing user entities.
 */
class UserService extends BaseService
{
    use Utils;

    /**
     * @var string The model this service pertains to.
     */
    protected $model = User::class;

    /**
     * @var string The resource class used for transforming user models into standardized API responses.
     */
    protected $resource = UserResource::class;

    /**
     * Retrieves a paginated list of users, including search and filter capabilities.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Returns a collection of user records as a resource collection.
     */
    public function get()
    {
        $users = $this->model::orderBy('id', 'desc')
            ->search(fieldNames: 'phone,email')
            ->paged();

        return $this->resource::collection($users);
    }

    /**
     * Updates the role of a specific user.
     *
     * @param string $id The ID of the user.
     * @param mixed $role The new role to be assigned to the user.
     * @return ApiResponse Returns ApiResponse indicating the result of the role update operation.
     */
    public function updateRole(string $id, mixed $role)
    {
        try {
            $user = $this->model::with('roles')->find($id);
            $user->syncRoles($role);

            return new ApiResponse('User role updated', 200, $this->resource::make($user));
        } catch (Throwable $e) {
            return new ApiResponse('Sorry, unable to update role', 500);
        }
    }

    /**
     * Updates the password for a specific user.
     *
     * @param string $id The ID of the user.
     * @param object $payload The new password and current password for verification.
     * @return ApiResponse Returns ApiResponse indicating the result of the password update operation.
     */
    public function updatePassword(string $id, object $payload)
    {
        try {
            $data = $this->model::with('roles')->find($id);

            if (!Hash::check($payload->current_password, $data->password)) {
                return new ApiResponse('Sorry, current password is incorrect', 400);
            }

            $data->update([
                'password' => Hash::make($payload->password),
                'has_set_password' => true,
            ]);

            return new ApiResponse('User password updated', 200, $this->resource::make($data));
        } catch (Throwable $e) {
            return new ApiResponse('Sorry, unable to update password', 500);
        }
    }

    /**
     * Creates a new user with the provided details.
     *
     * @param mixed $payload Data necessary for creating a new user.
     * @return ApiResponse Returns ApiResponse with user details on success or error message on failure.
     */
    public function create(mixed $payload)
    {
        try {
            $payload->password = Hash::make($payload->password);

            $user = $this->model::create((array) $payload);
            $user->assignRole($payload->role);

            return new ApiResponse('User created', 200, [
                'user' => $this->resource::make($user)
            ]);
        } catch (Throwable $e) {
            logger([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return new ApiResponse('Sorry, unable to create user', 500);
        }
    }

    /**
     * Updates a user with the provided details.
     *
     * @param string $id The ID of the user to be updated.
     * @param mixed $payload Data for updating the user.
     * @return ApiResponse Returns ApiResponse indicating the result of the user update operation.
     */
    public function update(string $id, mixed $payload)
    {
        try {
            $user = $this->model::with('roles')->find($id);
            $user->update((array) $payload);
            $user->syncRoles($payload->role);

            return new ApiResponse('User updated', 200, $this->resource::make($user));
        } catch (Throwable $e) {
            return new ApiResponse('Sorry, unable to update user', 500);
        }
    }
}
