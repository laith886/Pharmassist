<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Manufacturer;

class ManufacturerSeeder extends Seeder
{

    public function run(): void
    {
        $Manufacturers= [
            [
                'company_name' => 'Thamico',
                'location' => 'Lattakia',
                'phone' => '+1 212-555-1234',
                'email' => 'info@techsolutions.com',
                'website' => 'https://techsolutions.com',
            ],
            [
                'company_name' => 'Ibn Roushed',
                'location' => 'Tartus',
                'phone' => '+49 30 123456',
                'email' => 'contact@innovatech.de',
                'website' => 'https://innovatech.de',
            ],
            [
                'company_name' => 'Global Parts',
                'location' => 'Damascus',
                'phone' => '+81 3-1234-5678',
                'email' => 'hello@globalparts.jp',
                'website' => 'https://globalparts.jp',
            ],
        ];
        foreach($Manufacturers as $manufacturer){

        Manufacturer::create($manufacturer);

        }
    }
}
