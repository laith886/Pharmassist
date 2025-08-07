<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ManufacturerSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call(ManufacturerSeeder::class);
        $this->call(PharmacistSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MedicineSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(SalesRepresentativeSeeder::class);
        $this->call(MedicineCategorySeeder::class);



    }
}
