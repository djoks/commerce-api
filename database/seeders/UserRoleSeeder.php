<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $roles = Role::pluck('name')->all();
        $users = User::all();

        foreach ($users as $index => $user) {
            $role = ($index === 0) ? 'administrator' : 'customer';

            if ($index > 1) {
                $role = $roles[array_rand($roles)];
            }

            $user->assignRole($role);
        }
    }
}
