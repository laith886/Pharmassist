<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;
use App\Models\MedicineReturn;
use App\Models\Purchase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTodaySummary(): array
    {
        $today = Carbon::today();

        $salesTodayCount  = Sale::whereDate('sale_date', $today)->count();
        $salesTodayAmount = (float) Sale::whereDate('sale_date', $today)->sum('total_price');

        $returnsTodayQty = 0;
        if (Schema::hasTable('medicine_returns')) {
            $returnsTodayQty = (int) MedicineReturn::whereDate('created_at', $today)->sum('quantity_returned');
        }

        $purchasesToday = 0;
        if (Schema::hasTable('purchases')) {
            $purchasesToday = (int) Purchase::whereDate('purchase_date', $today)->count();
        }

        return [
            'sales_count'   => $salesTodayCount,
            'sales_amount'  => $salesTodayAmount,
            'returns_qty'   => $returnsTodayQty,
            'purchases_cnt' => $purchasesToday,
        ];
    }

    public function getLowStock(int $limit = 10)
    {
        return Medicine::select('id', 'name', 'quantity_in_stock', 'minimum_quantity')
            ->whereColumn('quantity_in_stock', '<=', 'minimum_quantity')
            ->orderBy('quantity_in_stock')
            ->limit($limit)
            ->get();
    }

    public function getExpiringSoon(int $days = 30, int $limit = 10)
    {
        $today = Carbon::today();
        return Medicine::select('id', 'name', 'expiration_Date')
            ->whereBetween('expiration_Date', [$today, $today->copy()->addDays($days)])
            ->orderBy('expiration_Date')
            ->limit($limit)
            ->get();
    }

    public function getTopMedicines(int $days = 30, int $limit = 5)
    {
        $since = Carbon::today()->subDays($days);

        return SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('medicines', 'sale_items.medicine_id', '=', 'medicines.id')
            ->whereDate('sales.sale_date', '>=', $since)
            ->groupBy('sale_items.medicine_id', 'medicines.name')
            ->select('medicines.name', \DB::raw('SUM(sale_items.quantity) as qty'))
            ->orderByDesc('qty')
            ->limit($limit)
            ->get();
    }

    public function getSalesTrend(int $days = 7): array
    {
        $end   = Carbon::today()->endOfDay();
        $start = Carbon::today()->subDays($days - 1)->startOfDay();

        $rows = Sale::query()
            ->selectRaw('DATE(sale_date) as d, SUM(total_price) as total')
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('d')
            ->orderBy('d')
            ->get();


        $labels = [];
        $values = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i)->toDateString();
            $labels[] = \Carbon\Carbon::parse($day)->format('M d');
            $values[] = (float) ($rows->firstWhere('d', $day)->total ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

     public function getTopManufacturers(int $limit = 6): array
{
    $rows = DB::table('manufacturers')
        ->leftJoin('medicines', 'medicines.manufacturer_id', '=', 'manufacturers.id')
        ->select(
            'manufacturers.company_name as name',      // 👈 Alias واضح
            DB::raw('COUNT(medicines.id) AS cnt')
        )
        ->groupBy('manufacturers.id', 'manufacturers.company_name')
        ->orderByDesc('cnt')
        ->limit($limit)
        ->get();

    return [
        'labels' => $rows->pluck('name')->values(),                 // 👈 الآن ليست null
        'values' => $rows->pluck('cnt')->map(fn($v) => (int)$v)->values(),
    ];
}

}
