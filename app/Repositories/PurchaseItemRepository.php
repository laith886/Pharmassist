<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PurchaseItemsRepositoryInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

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
                'price' => 0, //the price is zero because the sales representative should enter it
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

    // Header row
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

    // Create data validation rule for numeric values (price >= 0)
    $priceValidation = new DataValidation();
    $priceValidation->setType(DataValidation::TYPE_DECIMAL);
    $priceValidation->setErrorStyle(DataValidation::STYLE_STOP);
    $priceValidation->setAllowBlank(true);
    $priceValidation->setShowInputMessage(true);
    $priceValidation->setShowErrorMessage(true);
    $priceValidation->setErrorTitle('Invalid Input');
    $priceValidation->setError('Only numeric values are allowed.');
    $priceValidation->setPromptTitle('Price');
    $priceValidation->setPrompt('Please enter a valid number.');
    $priceValidation->setOperator(DataValidation::OPERATOR_GREATERTHANOREQUAL);
    $priceValidation->setFormula1('0');

    // Apply validation and unlock cells from E2 to E1000
    for ($r = 2; $r <= 1000; $r++) {
        $cell = "E{$r}";
        $sheet->getCell($cell)->setDataValidation(clone $priceValidation);
        $sheet->getStyle($cell)->getProtection()->setLocked(false);
    }

    // Protect the sheet but allow editing of unlocked cells
    $sheet->getProtection()->setSheet(true);
    $sheet->getProtection()->setPassword('12345');
    $sheet->getProtection()->setInsertRows(false);
    $sheet->getProtection()->setInsertColumns(false);
    $sheet->getProtection()->setDeleteRows(false);
    $sheet->getProtection()->setDeleteColumns(false);

    // Save the file
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

    public function ImportPricedSupplyOrder($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestRow();

        $purchaseId = $sheet->getCell("A2")->getValue();
        if (!$purchaseId) {
            return ['status' => false, 'message' => 'Purchase ID not found in file'];
        }

        $totalCost = 0;

        for ($row = 2; $row <= $highestRow; $row++) {
            $medicineId = $sheet->getCell("B{$row}")->getValue();
            $price = $sheet->getCell("E{$row}")->getValue();

            if ($price <= 0) {
                return ['status' => false, 'message' => "Invalid price for medicine ID: $medicineId"];
            }

            $purchaseItem = PurchaseItem::where('purchase_id', $purchaseId)
                ->where('medicine_id', $medicineId)
                ->first();

            if (!$purchaseItem) continue;

            $purchaseItem->price = $price;
            $purchaseItem->save();

            //$totalCost += $purchaseItem->quantity * $price;
        }

        $purchase = Purchase::findOrFail($purchaseId);
        //$purchase->total_cost = $totalCost;
        $purchase->status_id = 1; // حالة "تم التسعير" LAITH changed to 2 don`t forget
        $purchase->save();

        return ['status' => true, 'message' => 'Prices imported successfully'];
    }

}
