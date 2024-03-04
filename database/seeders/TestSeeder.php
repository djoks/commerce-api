<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\Utils;
use App\Models\Billing;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\PaymentType;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    use Utils;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Add test user record
        $user = User::create([
            'email' => 'test@example.com',
            'phone' => $this->generateGhanaianPhoneNumber(),
            'password' => bcrypt('password'),
            'has_set_password' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole(['developer']);

        // Add test client record
        Billing::create([
            'customer_id' => $user->id,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'street_address' => $faker->streetAddress,
            'city' => $faker->city,
            'state' => $faker->state,
            'country' => 'Ghana',
        ]);

        // Add test supplier record
        Supplier::create([
            'name' => 'Test',
            'phone' => $this->generateGhanaianPhoneNumber(),
            'email' => 'test@example.com',
            'address' => $faker->address,
            'contact_person_name' => $faker->name,
            'contact_person_phone' => $this->generateGhanaianPhoneNumber(),
            'contact_person_email' => $faker->email,
        ]);

        // Add test equipment brand record
        Category::create(['name' => 'Test']);

        // Add test equipment record
        $categories = Category::all();

        $costPrice = $faker->numberBetween(100, 1000);
        $sellingPrice = $faker->numberBetween($costPrice + 1, 1000);
        $notes = $faker->optional()->realText();

        $category = $faker->randomElement($categories);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'barcode' => $faker->ean13,
            'cost_price' => $costPrice,
            'selling_price' => $sellingPrice,
            'status' => 'Out Of Stock',
            'notes' => $notes
        ]);
    }
}
