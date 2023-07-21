<?php

namespace App\Services;

use App\Http\Resources\RoleResource;
use App\Models\ApiResponse;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleService extends BaseService
{
    protected $model = Role::class;

    protected $resource = RoleResource::class;

    public function get()
    {
        $userId = request('user_id');
        if ($userId) {
            return $this->getUserRole($userId);
        }

        $data = $this->model::orderBy('name', 'asc')->with('permissions')->get();

        return $this->resource::collection($data);
    }

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
