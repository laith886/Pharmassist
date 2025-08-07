<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Pain Relievers'],
            ['category_name' => 'Antibiotics'],
            ['category_name' => 'Vitamins'],
            ['category_name' => 'Skin Care'],
            ['category_name' => 'Cold and Flu'],
            ['category_name' => 'Digestive Health'],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }
    }

}
