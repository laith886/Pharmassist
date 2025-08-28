<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'manufacturer_id' => 1,
                'prescription' => 'Not required',
                'production_Date' => '2024-01-15',
                'expiration_Date' => '2026-01-15',
                'quantity_in_stock' => 500,
                'minimum_quantity' => 100,
                'price' => 3.50,
                'sci_name' => 'Acetaminophen',
            ],
            [
                'name' => 'Amoxicillin 250mg',
                'manufacturer_id' => 2,
                'prescription' => 'Required',
                'production_Date' => '2024-03-01',
                'expiration_Date' => '2026-03-01',
                'quantity_in_stock' => 300,
                'minimum_quantity' => 50,
                'price' => 6.75,
                'sci_name' => 'Amoxicillin',
            ],
            [
                'name' => 'Vitamin C 1000mg',
                'manufacturer_id' => 3,
                'prescription' => 'Not required',
                'production_Date' => '2024-06-01',
                'expiration_Date' => '2027-06-01',
                'quantity_in_stock' => 800,
                'minimum_quantity' => 200,
                'price' => 2.25,
                'sci_name' => 'Ascorbic Acid',
            ],
        ];

        foreach ($medicines as $data) {
            Medicine::create($data);
        }
    }
}
