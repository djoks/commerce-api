<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Billing;
use App\Models\PaymentType;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $users = User::role('customer')->get();

        foreach ($users as $user) {
            // Determine the number of billing addresses for the user (between 0 and 2)
            $numAddresses = rand(0, 2);

            for ($i = 0; $i < $numAddresses; $i++) {
                $billing = [
                    'customer_id' => $user->id,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'street_address' => $faker->streetAddress,
                    'city' => $faker->city,
                    'state' => $faker->state,
                    'country' => 'Ghana'
                ];

                Billing::create($billing);
            }

            // Set one billing address as default (if there are multiple addresses)
            if ($numAddresses > 1) {
                $user->billings()->first()->update(['is_default' => true]);
            }
        }
    }
}
