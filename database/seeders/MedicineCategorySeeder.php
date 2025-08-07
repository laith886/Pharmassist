<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicineCategory;

class MedicineCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $entries = [
            ['medicine_id' => 1, 'category_id' => 1], // Paracetamol → Pain Relievers
            ['medicine_id' => 2, 'category_id' => 2], // Amoxicillin → Antibiotics
            ['medicine_id' => 3, 'category_id' => 3], // Vitamin C → Vitamins
            ['medicine_id' => 1, 'category_id' => 5], // Paracetamol → Cold and Flu
        ];

        foreach ($entries as $data) {
            MedicineCategory::create($data);
        }
    }
}
