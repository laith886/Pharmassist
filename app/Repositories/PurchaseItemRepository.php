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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;

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
    'status_id'=>'1'
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

    private function export(array $purchaseItems, Purchase $purchase)
{
     $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray(['Purchase_id', 'Medicine_id', 'Medicine Name', 'Quantity', 'Price'], null, 'A1');

    $row = 2;
    foreach ($purchaseItems as $item) {
        $sheet->setCellValue("A{$row}", $purchase->id);
        $sheet->setCellValue("B{$row}", $item['medicine']->id);
        $sheet->setCellValue("C{$row}", $item['medicine']->name);
        $sheet->setCellValue("D{$row}", $item['quantity']);
        $sheet->setCellValue("E{$row}", $item['price'] ?? 0);
        $row++;
    }


    $highestRow = $sheet->getHighestRow();

    for ($r = 2; $r <= $highestRow; $r++) {

        $sheet->getCell("E{$r}")->getStyle()->getProtection()->setLocked(false);
    }


    $sheet->getProtection()->setSheet(true);
    $sheet->getProtection()->setPassword('12345');
    $sheet->getProtection()->setInsertRows(false);
    $sheet->getProtection()->setInsertColumns(false);
    $sheet->getProtection()->setDeleteRows(false);
    $sheet->getProtection()->setDeleteColumns(false);

    $filename = "SupplyOrder_{$purchase->id}.xlsx";
    $path = storage_path("app/public/{$filename}");

    $writer = new Xlsx($spreadsheet);
    $writer->save($path);

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
