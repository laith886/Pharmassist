<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Pharmacist;
use App\Models\Sale;

class GetPharmacistSales extends JsonResource
{
    
    public function toArray(Request $request): array
    {
          $pharmacist=Pharmacist::find($this->pharmacist_id);

            return [
                   'sale_id' => $this->id,
                   'pharmacist' => $pharmacist? $pharmacist->first_name . ' ' . $pharmacist->last_name: null,
                   'sale_date' => $this->sale_date,
                   'items' => $this->salesItems ? $this->salesItems->map(function ($item) {
                        return [
                        'Medicine_id'=>$item->id,
                        'medicine_name' => optional($item->medicine)->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
    ];
})->values() : collect([]), ];

    }
}
