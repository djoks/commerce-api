<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\ApiResponse;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService extends BaseService
{
    use Utils;

    protected $model = User::class;

    protected $resource = UserResource::class;

    public function get()
    {
        $users = $this->model::orderBy('id', 'desc')
            ->search(fieldNames: 'phone,email')
            ->paged();

        return $this->resource::collection($users);
    }

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
