<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Traits\Utils;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Models\ProductStock;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    use Utils;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Generate a random number of orders (between 1 and 100) for each user
        $orderCount = rand(1, 350);
        $total = 0;

        for ($i = 0; $i < $orderCount; $i++) {
            $user = User::role('customer')
                ->with('billings')
                ->whereHas('billings')
                ->inRandomOrder()
                ->first();

            $billing = $user->billings[0];

            $date = $this->generateRandomDate();

            $shipping = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'street_address' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->state,
                'country' => $faker->country
            ];

            // Create a new order
            $order = Order::create([
                'customer_id' => $user->id,
                'billing_id' => $billing->id,
                'code' => $this->generateOrderNo(),
                'sub_total' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
                'meta' => null,
                'shipping' => $shipping,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Generate a random number of order items (between 1 and 10) for each order
            $itemCount = rand(1, 10);

            for ($j = 0; $j < $itemCount; $j++) {
                // Get a random equipment and equipment stock from the current branch
                $product = Product::whereStatus('Available')->inRandomOrder()->first();

                $quantity = rand(1, 3);

                // Create a new order item with the price from equipment's selling_price
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->selling_price,
                    'quantity' => $quantity,
                    'amount' => ($product->selling_price * $quantity),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                $this->updateStockLevel($product, $quantity);

                $total = $total + $product->selling_price;
            }

            // Save totals
            $order->sub_total = $total;
            $order->discount = rand(0, 1) === 1 ? mt_rand(1000, 50000) : 0;
            $order->tax = (21.9 / 100) * $total;
            $order->total = ($order->sub_total - $order->discount) + $order->tax;
            $order->save();

            Payment::create([
                'order_id' => $order->id,
                'reference' => Str::upper(Str::random(12)),
                'status' => 'Paid',
                'meta' => null,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    private function generateRandomDate()
    {
        // Generate a random date between now and 12 months ago
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now();

        return Carbon::createFromTimestamp(mt_rand($startDate->timestamp, $endDate->timestamp));
    }

    private function updateStockLevel(Product $product, int $quantity)
    {
        $stock = ProductStock::where('product_id', $product->id)
            ->where('available_quantity', '>', 0)
            ->orderBy('created_at')
            ->first();

        $stock->available_quantity = ($stock->available_quantity - $quantity);
        $stock->save();
    }
}
