<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Clothing'],
            ['name' => 'Home Appliances'],
            ['name' => 'Books'],
            ['name' => 'Furniture'],
            ['name' => 'Toys'],
            ['name' => 'Sports Equipment'],
            ['name' => 'Beauty Products'],
            ['name' => 'Automotive Parts'],
            ['name' => 'Pet Supplies'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
