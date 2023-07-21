<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Traits\Utils;

class UserSeeder extends Seeder
{
    use Utils;

    public function run()
    {
        $faker = Faker::create();

        $users = [];

        $user = [
            'email' => 'admin@example.com',
            'phone' => $this->generateGhanaianPhoneNumber(),
            'password' => bcrypt('password'),
            'has_set_password' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $users[] = $user;

        $user = [
            'email' => 'customer@example.com',
            'phone' => $this->generateGhanaianPhoneNumber(),
            'password' => bcrypt('password'),
            'has_set_password' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $users[] = $user;

        // Generate additional users
        for ($i = 1; $i < 25; $i++) {
            $user = [
                'email' => $faker->safeEmail(),
                'phone' => $this->generateGhanaianPhoneNumber(),
                'password' => bcrypt('password'),
                'has_set_password' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $users[] = $user;
        }

        User::insert($users);
    }
}
