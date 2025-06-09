<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PurchaseItemsRepositoryInterface;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Medicine;
use Illuminate\Support\Facades\Storage;
use App\Models\SaleRepresentative;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSupplyOrder;

class PurchaseItemRepository implements PurchaseItemsRepositoryInterface
{


private function CheckQuantities(array $items): array
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
                'price' => 0, //the price is zero because the sales representative shoul enter it
            ];
        }

        return ['status' => true, 'data' => $PurchaseItems];
    }

private function CreatePurchase(array $items){

    $saleRep = SaleRepresentative::with('warehouse')->findOrFail($items['sale_representative_id']);

    return Purchase::create([
    'pharmacist_id' =>Auth::id(),
    'sale_representative_id'=>$items['sale_representative_id'],
    'warehouse_id'=> $saleRep->warehouse_id,
    'purchase_date'=>now(),
    'status_id'=>'1' //Pending
    ]);
}

private function CreatePurchaseItems(Purchase $purchase,array $items){

    $purchaseItems=[];

    foreach($items as $item){


        PurchaseItem::create([
            'purchase_id'=>$purchase->id,
            'medicine_id'=>$item['medicine']->id,
            'quantity'=>$item['quantity'],
            'price' => 0
        ]);


        $purchaseItems[] = [
                'medicine' => $item['medicine'],
                'quantity' => $item['quantity'],
            ];



    }


    return $purchaseItems;



}
public function export(array $purchaseItems,Purchase $purchase)
   {
       $filename = "SupplyOrder_{$purchase->id}.csv";
       $path = storage_path("app/public/{$filename}");
       $handle = fopen($path, 'w');

       fputcsv($handle, ['Purchase_id','Medicine_id','Medicine Name', 'Quantity','price']);

       foreach ($purchaseItems as $item) {
           fputcsv($handle,
           [$purchase->id,
           $item['medicine']->id,
            $item['medicine']->name,
             $item['quantity'],
              $item['price'] ?? 0]);
       }

       fclose($handle);

       return $path;
}

public function MakeSupplyOrder(array $items){

    $check=$this->CheckQuantities($items['items']);

    if (!$check['status']) {
        return $check;
    }

     $purchase = $this->CreatePurchase($items);

     $purchaseItems = $this->CreatePurchaseItems($purchase, $check['data']);

     $filePath = $this->export($purchaseItems,$purchase);

     $representative = SaleRepresentative::find($items['sale_representative_id']);

     $email = $representative->email;

     Mail::to($email)->send(new SendSupplyOrder($filePath));


    return ['message' => 'Request sent successfully'];
}










}
