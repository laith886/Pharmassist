<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PurchaseItemsRepositoryInterface;
use App\Http\Requests\MakeSupplyOrderRequest;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Medicine;
class PurchaseItemRepository implements PurchaseItemsRepositoryInterface
{


private function checkQuantities(array $items): array
    {
        $PurchaseItems = [];

        foreach ($items as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if (!$medicine ||  $item['quantity'] < 1) {
                return [
                    'status' => false,
                    'message' => "ID : the quantity is invalid {$item['medicine_id']}"
                ];
            }

            $PurchaseItems[] = [
                'medicine' => $medicine,
                'quantity' => $item['quantity'],
                'price' => $medicine->price,
            ];
        }

        return ['status' => true, 'data' => $PurchaseItems];
    }

private function CreatePurchase(array $items){

    return Purchase::create([
    'pharmaicst_id'=>Auth::id(),
    'sale_representative_id'=>$items['sale_representative_id'],
    'warehous_id'=>$items['warehouse_id'],
    'purchase_date'=>now(),
    'status_id'=>'1'
    ]);
}

private function CreatePurchaseItems(Purchase $purchase,array $items){


    foreach($items as $item){
        PurchaseItem::create([
            'purchase_id'=>$purchase->id,
            'medicine_id'=>$item['medicine']->id,
            'quantity'=>$item['quantity']
        ]);
    }






}
public function MakeSupplyOrder(array $items){

    $check=$this->CheckQuantity($items);

    if (!$check['status']) {
        return $check;
    }



}

}
