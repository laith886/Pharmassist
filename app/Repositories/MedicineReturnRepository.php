<?php
namespace App\Repositories;

use App\Repositories\Interfaces\MedicineReturnRepositoryInterface;
use App\Models\MedicineReturn;
use App\Models\SaleItem;
use App\Models\Medicine;

class MedicineReturnRepository implements MedicineReturnRepositoryInterface
{
    public function getAll()
    {
        return MedicineReturn::with(['sale', 'saleItem'])->get();
    }

    public function getBySaleId($saleId)
    {
        return MedicineReturn::where('sale_id', $saleId)->with('saleItem')->get();
    }

    public function store(array $data)
    {
        $saleItem = SaleItem::findOrFail($data['sale_item_id']);

         if ($saleItem->sale_id !== $data['sale_id']) {
        throw new \Exception('this medicine do not belong to this bill');
    }


        // here I am calculating the ammount that i can return
        $returnedQty = $saleItem->medicineReturns()->sum('quantity_returned');
        $maxReturnable = $saleItem->quantity - $returnedQty;


        if ($data['quantity_returned'] > $maxReturnable) {
            throw new \Exception('the quantity is more than the saled ');
        }


        $return = MedicineReturn::create([
            'sale_id' => $data['sale_id'],
            'sale_item_id' => $data['sale_item_id'],
            'quantity_returned' => $data['quantity_returned'],
            'reason' => $data['reason'] ?? null,
            'returned_at' => now(),
        ]);


        $medicine = Medicine::findOrFail($saleItem->medicine_id);
        $medicine->quantity_in_stock += $data['quantity_returned'];
        $medicine->save();

        return $return;
    }
}
