<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Central Warehouse',
                'location' => 'Homs',
                'phone' => '+1 212-555-7890',
            ],
            [
                'name' => 'West Coast Storage',
                'location' => 'Damascus',
                'phone' => '+1 310-555-1234',
            ],
            [
                'name' => 'European Hub',
                'location' => 'Rif Dimashq',
                'phone' => '+49 30 567890',
            ],
        ];

        foreach ($warehouses as $data) {
            Warehouse::create($data);
        }
    }
}
