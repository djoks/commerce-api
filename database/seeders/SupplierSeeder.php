<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 45; $i++) {
            $supplier = [
                'name' => $faker->company,
                'phone' => $this->generateGhanaianPhoneNumber(),
                'email' => $faker->email,
                'address' => $faker->address,
                'contact_person_name' => $faker->name,
                'contact_person_phone' => $this->generateGhanaianPhoneNumber(),
                'contact_person_email' => $faker->email,
            ];

            Supplier::create($supplier);
        }
    }

    private function generateGhanaianPhoneNumber()
    {
        // Generate a random Ghanaian phone number
        $prefixes = ['024', '054', '055', '059'];
        $prefix = $prefixes[array_rand($prefixes)];
        $number = mt_rand(1000000, 9999999);

        return $prefix . $number;
    }
}
