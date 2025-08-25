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
    if (empty($data['sale_id'])) {
        throw new \Exception('sale_id is required.');
    }

    // دعم الشكل القديم (عنصر واحد) أو الجديد (items[])
    $items = $data['items'] ?? [[
        'sale_item_id'      => $data['sale_item_id'] ?? null,
        'quantity_returned' => $data['quantity_returned'] ?? null,

    ]];

    // تحضير: سنتأكد من صحة كل عنصر قبل أي تعديل فعلي
    $prepared = [];
    $plannedPerSaleItem = []; // لتجنّب تجاوز الكمية إذا تكرر نفس sale_item داخل نفس الطلب

    foreach ($items as $index => $row) {
        // تحقق من الإدخالات الأساسية
        if (empty($row['sale_item_id'])) {
            throw new \Exception('sale_item_id is required for item #'.($index+1).'.');
        }

        $qty = (int) ($row['quantity_returned'] ?? 0);
        if ($qty <= 0) {
            throw new \Exception('quantity_returned must be > 0 for item #'.($index+1).'.');
        }

        // أحضر الـ SaleItem وتأكد أنه يتبع نفس الفاتورة
        $saleItem = SaleItem::findOrFail($row['sale_item_id']);
        if ((int) $saleItem->sale_id !== (int) $data['sale_id']) {
            throw new \Exception('This sale item does not belong to this bill (item #'.($index+1).').');
        }

        // الكمية التي تم إرجاعها مسبقًا
        $returnedBefore = (int) $saleItem->medicineReturns()->sum('quantity_returned');

        // الكمية المخطط لها بالفعل لنفس العنصر داخل نفس الطلب
        $plannedNow = (int) ($plannedPerSaleItem[$saleItem->id] ?? 0);

        // أقصى كمية قابلة للإرجاع
        $maxReturnable = (int) $saleItem->quantity - $returnedBefore - $plannedNow;

        if ($qty > $maxReturnable) {
            throw new \Exception(
                "The quantity for item #".($index+1)." exceeds what was sold. Max returnable: {$maxReturnable}."
            );
        }

        // خزّن العنصر للتحويل لاحقًا
        $prepared[] = [
            'saleItem' => $saleItem,
            'qty'      => $qty,

        ];

        // حدِّث المخطط المحلي
        $plannedPerSaleItem[$saleItem->id] = $plannedNow + $qty;
    }

    // المرحلة الثانية: تنفيذ الإنشاء وتحديث المخزون (بدون Transaction)
    $createdReturns = [];
    foreach ($prepared as $p) {
        $created = MedicineReturn::create([
            'sale_id'           => $data['sale_id'],
            'sale_item_id'      => $p['saleItem']->id,
            'quantity_returned' => $p['qty'],
            'returned_at'       => now(),
        ]);

        Medicine::where('id', $p['saleItem']->medicine_id)
            ->increment('quantity_in_stock', $p['qty']);

        $createdReturns[] = $created;
    }

    // بإمكانك ترجع Collection أو Array حسب تفضيلك
    return collect($createdReturns);
}

}
