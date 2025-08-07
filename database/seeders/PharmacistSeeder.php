<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pharmacist;
use Illuminate\Support\Facades\Hash;

class PharmacistSeeder extends Seeder
{

    public function run(): void
    {
        $pharmacists = [
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'username' => 'alice_j',
                'password' => Hash::make('password123'), // hashed
                'phone' => '+1 555-123-4567',
                'employment_date' => now()->subYears(2),
                'salary' => 4500.00,
                'is_admin' => true,
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Smith',
                'username' => 'bob_smith',
                'password' => Hash::make('securepass'), // hashed
                'phone' => '+1 555-987-6543',
                'employment_date' => now()->subYear(),
                'salary' => 4000.00,
                'is_admin' => false,
            ],
            [
                'first_name' => 'Clara',
                'last_name' => 'Davis',
                'username' => 'clara_d',
                'password' => Hash::make('admin2024'), // hashed
                'phone' => '+1 555-444-2222',
                'employment_date' => now()->subMonths(6),
                'salary' => 4200.00,
                'is_admin' => false,
            ],
        ];

        foreach ($pharmacists as $data) {
            Pharmacist::create($data);
        }
    }
}
