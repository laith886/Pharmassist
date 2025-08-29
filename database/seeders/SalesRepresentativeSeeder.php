<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SaleRepresentative;

class SalesRepresentativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $reps = [
            [
                'name' => 'John Carter',
                'phone' => '+1 555-123-4567',
                'email'=>'ibrahim.hana22@gmail.com',
                'warehouse_id' => 1,
            ],
            [
                'name' => 'Maria Lopez',
                'phone' => '+1 555-987-6543',
                'email'=>'login.login9838@gmail.com',
                'warehouse_id' => 2,
            ],
            [
                'name' => 'Ahmed Khan',
                'phone' => '+49 30 5555 1234',
                'email'=>'sukaplatz@gmail.com',
                'warehouse_id' => 3,
            ],
        ];

        foreach ($reps as $data) {
            SaleRepresentative::create($data);
        }
    }
}
