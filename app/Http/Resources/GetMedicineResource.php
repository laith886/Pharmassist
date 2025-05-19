<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetMedicineResource extends JsonResource
{

    public function toArray($request)
    {

        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->name;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'sci_name' => $this->sci_name,
            'price' => $this->price,
            'prescription' => $this->prescription,
            'quantity_in_stock' => $this->quantity_in_stock,
            'categories' => $categories,
            'Manufacturer'=>$this->manufacturer()->company_name
        ];
    }

}
