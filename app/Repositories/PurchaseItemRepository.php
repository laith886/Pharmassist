<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PurchaseItemsRepositoryInterface;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\SaleRepresentative;
use App\Mail\SendSupplyOrder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Facades\DB;
use App\Models\Medicine;

class PurchaseItemRepository implements PurchaseItemsRepositoryInterface
{
    protected MedicineRepositoryInterface $medicines;

    public function __construct(MedicineRepositoryInterface $medicines)
    {
        $this->medicines = $medicines;
    }

    /**
     * Pick a single Medicine by name using the repository's findByName().
     * - Prefer exact (case-insensitive) match if present.
     * - If none exact:
     *     - if 1 fuzzy match -> use it
     *     - if 0 -> null
     *     - if >1 -> throw ambiguity
     */
    private function resolveMedicineByName(string $name): array
    {
        $candidates = $this->medicines->findByName($name); // Collection
        if ($candidates->isEmpty()) {
            return ['status' => false, 'error' => "Medicine '{$name}' not found."];
        }

        $lower = mb_strtolower(trim($name));
        $exact = $candidates->first(function ($m) use ($lower) {
            return mb_strtolower($m->name) === $lower;
        });

        if ($exact) {
            return ['status' => true, 'medicine' => $exact];
        }

        if ($candidates->count() === 1) {
            return ['status' => true, 'medicine' => $candidates->first()];
        }

        $names = $candidates->pluck('name')->take(5)->implode(', ');
        return [
            'status' => false,
            'error' => "Medicine name '{$name}' is ambiguous. Did you mean: {$names} ...?"
        ];
    }

    private function CheckQuantities(array $items): array
    {
        $PurchaseItems = [];

        foreach ($items as $idx => $item) {
            // Expect medicine_name instead of medicine_id
            if (!isset($item['medicine_name']) || trim($item['medicine_name']) === '') {
                return [
                    'status' => false,
                    'message' => "Item #".($idx+1).": 'medicine_name' is required."
                ];
            }
            if (!isset($item['quantity']) || !is_numeric($item['quantity']) || (int)$item['quantity'] < 1) {
                return [
                    'status' => false,
                    'message' => "Item #".($idx+1).": quantity must be >= 1."
                ];
            }

            $resolved = $this->resolveMedicineByName($item['medicine_name']);
            if (!$resolved['status']) {
                return ['status' => false, 'message' => $resolved['error']];
            }

            $PurchaseItems[] = [
                'medicine' => $resolved['medicine'],
                'quantity' => (int)$item['quantity'],
                'price'    => 0, // sales representative will fill later
            ];
        }

        return ['status' => true, 'data' => $PurchaseItems];
    }

    private function CreatePurchase(array $items)
    {
        $saleRep = SaleRepresentative::with('warehouse')->findOrFail($items['sale_representative_id']);

        return Purchase::create([
            'pharmacist_id'          => Auth::id(),
            'sale_representative_id' => $items['sale_representative_id'],
            'warehouse_id'           => $saleRep->warehouse_id,
            'purchase_date'          => now(),
            'status_id'              => '1',
        ]);
    }

    private function CreatePurchaseItems(Purchase $purchase, array $items)
    {
        $purchaseItems = [];

        foreach ($items as $item) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'medicine_id' => $item['medicine']->id,
                'quantity'    => $item['quantity'],
                'price'       => 0,
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

        // Price validation (>= 0)
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

        for ($r = 2; $r <= 1000; $r++) {
            $cell = "E{$r}";
            $sheet->getCell($cell)->setDataValidation(clone $priceValidation);
            $sheet->getStyle($cell)->getProtection()->setLocked(false);
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

    public function MakeSupplyOrder(array $items)
    {
        $check = $this->CheckQuantities($items['items'] ?? []);

        if (!$check['status']) {
            return $check;
        }

        $purchase      = $this->CreatePurchase($items);
        $purchaseItems = $this->CreatePurchaseItems($purchase, $check['data']);
        $filePath      = $this->export($purchaseItems, $purchase);

        $representative = SaleRepresentative::find($items['sale_representative_id']);
        $email = $representative?->email;

        if ($email) {
            Mail::to($email)->send(new SendSupplyOrder($filePath));
        }

        return ['message' => 'Request sent successfully'];
    }

 public function ImportPricedSupplyOrder($filePath)
{
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();

    $purchaseId = trim((string) $sheet->getCell('A2')->getCalculatedValue());
    if ($purchaseId === '') {
        return ['status' => false, 'message' => 'Purchase ID not found in file'];
    }

    // Only iterate over rows that actually have data in A, B, or E
    $lastRow = max(
        (int) $sheet->getHighestDataRow('A'),
        (int) $sheet->getHighestDataRow('B'),
        (int) $sheet->getHighestDataRow('E')
    );
    if ($lastRow < 2) {
        return ['status' => false, 'message' => 'No items found in file'];
    }

    $errors = [];
    DB::beginTransaction();

    try {
        for ($row = 2; $row <= $lastRow; $row++) {
            $medicineId = trim((string) $sheet->getCell("B{$row}")->getCalculatedValue());
            $rawPrice   = $sheet->getCell("E{$row}")->getFormattedValue(); // what user sees (may be text)

            // Skip empty lines
            if ($medicineId === '' && (is_null($rawPrice) || trim((string)$rawPrice) === '')) {
                continue;
            }

            // Normalize the line TOTAL value (accept 1 200, 1,200.50, 1200,50, etc.)
            $priceStr  = trim((string) $rawPrice);
            $priceStr  = preg_replace('/[^\d,.\-]/', '', $priceStr);
            $priceStr  = str_replace(',', '.', $priceStr);
            $lineTotal = is_numeric($priceStr) ? (float) $priceStr : null;

            if ($medicineId === '' || !ctype_digit($medicineId)) {
                $errors[] = "Row {$row}: invalid or empty medicine ID.";
                continue;
            }
            if ($lineTotal === null || $lineTotal <= 0) {
                $errors[] = "Row {$row}: invalid total price '{$rawPrice}' for medicine ID {$medicineId}.";
                continue;
            }

            /** @var PurchaseItem|null $purchaseItem */
            $purchaseItem = PurchaseItem::where('purchase_id', (int)$purchaseId)
                ->where('medicine_id', (int)$medicineId)
                ->first();

            if (!$purchaseItem) {
                $errors[] = "Row {$row}: item not found for medicine ID {$medicineId} in purchase {$purchaseId}.";
                continue;
            }

            $qty = max(1, (int) $purchaseItem->quantity);

            // === compute UNIT price from line TOTAL ===
            $unitPrice = round($lineTotal / $qty, 2);

            // Save unit price on purchase item (price column = unit price)
            $purchaseItem->price = $unitPrice;
            $purchaseItem->save();

            // === increase medicine stock ===
            $medicine = Medicine::find((int)$medicineId);
            if ($medicine) {
                $medicine->increment('quantity_in_stock', $qty);

                // OPTIONAL: update catalog/unit price on the medicine record
                $medicine->price = $unitPrice;
                $medicine->save();
            }
        }

        if (!empty($errors)) {
            DB::rollBack();
            return ['status' => false, 'message' => 'Import finished with errors.', 'errors' => $errors];
        }

        // Mark purchase as "priced"
        $purchase = Purchase::findOrFail((int)$purchaseId);
        $purchase->status_id = 2;
        $purchase->save();

        DB::commit();
        return ['status' => true, 'message' => 'Prices imported, stock updated, and unit prices set.'];
    } catch (\Throwable $e) {
        DB::rollBack();
        return ['status' => false, 'message' => 'Unexpected error: '.$e->getMessage()];
    }}
}
