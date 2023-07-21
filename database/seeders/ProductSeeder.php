<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Faker\Factory as Faker;
use App\Models\ProductStock;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $categories = Category::all();
        $suppliers = Supplier::all();

        foreach ($categories as $category) {
            for ($i = 0; $i < rand(1, 25); $i++) {
                $costPrice = $faker->numberBetween(100, 1000);
                $sellingPrice = $faker->numberBetween($costPrice + ($costPrice  * 0.05), $costPrice + ($costPrice  * 0.25));
                $status = $faker->randomElement(['Available', 'Out Of Stock']);

                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $faker->sentence(2),
                    'barcode' => $faker->ean13,
                    'cost_price' => $costPrice,
                    'selling_price' => $sellingPrice,
                    'status' => $status,
                    'notes' => $faker->paragraph,
                ]);

                for ($j = 0; $j < rand(1, 5); $j++) {
                    $quantity = $faker->numberBetween(100, 1000);

                    ProductStock::create([
                        'supplier_id' => $suppliers->random()->id,
                        'product_id' => $product->id,
                        'initial_quantity' => $quantity,
                        'available_quantity' => $status === 'Out Of Stock' ? 0 : $quantity,
                        'purchase_date' => Carbon::now()->subMonths(rand(1, 5)),
                        'manufacture_date' => Carbon::now()->subMonths(rand(6, 12)),
                        'expiry_date' => $faker->dateTimeBetween('+1 years', '+5 years'),
                        'notes' => $faker->paragraph,
                    ]);
                }
            }
        }
    }
}
