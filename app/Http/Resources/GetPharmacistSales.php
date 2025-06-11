<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPharmacistSales extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        
            return [
            'id' => $this->id,
            'sale_id' => $this->sale_id,
            'medicine_name' => $this->medicine->name, // اسم الدواء من العلاقة
            'quantity' => $this->quantity,
            'price' => $this->price,];
    }
}
