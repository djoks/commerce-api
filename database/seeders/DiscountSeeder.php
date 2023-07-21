<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::create([
            'name' => 'Happy Discount',
            'code' => Str::upper(Str::random(6)),
            'value' => 10,
            'unit' => 'percentage',
            'max_value' => 45000
        ]);

        Discount::create([
            'name' => 'New Discount',
            'code' => Str::upper(Str::random(6)),
            'value' => 500,
            'unit' => 'ghs'
        ]);
    }
}
