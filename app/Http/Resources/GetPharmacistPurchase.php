<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Pharmacist;

class GetPharmacistPurchase extends JsonResource
{

    public function toArray(Request $request): array
    {
        $pharmacist=Pharmacist::find($this->pharmacist_id);

        return [
        'purchase_id' => $this->id,
        'pharmacist'=>$pharmacist->first_name . ' ' . $pharmacist->last_name,
        'Purchase Date'=>$this->purchase_date,
        'items' => $this->purchaseItems->map(function ($item) {
            return [
                'medicine_name' => optional($item->medicine)->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        })->values(), 
    ];










}



}
