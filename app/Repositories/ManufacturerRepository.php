<?php

namespace App\Repositories;

use App\Models\Manufacturer;
use App\Repositories\Interfaces\ManufactururRepositoryInterface;

class ManufacturerRepository implements ManufactururRepositoryInterface
{



   public function GetManufacturerMedicines(string $ManufacturerName){

       $manufacurer=Manufacturer::where('company_name',$ManufacturerName)->first();
        if(!$manufacurer){
            return null;
        }
      return $medicines=$manufacurer->medicines;


   }




}
