<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllMedicines extends JsonResource
{

    public function toArray(Request $request): array
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->category_name;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'sci_name' => $this->sci_name,
            'price' => $this->price,
            'prescription' => $this->prescription,
            'quantity_in_stock' => $this->quantity_in_stock,
            'production_date'=>$this->production_date,
            'expiration_date'=>$this->expiration_date,
            'categories' => $categories,
            'Manufacturer'=>$this->manufacturer->company_name
        ];
    }
}
