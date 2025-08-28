<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\MedicineReturn;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ReportRepositoryInterface;


class ReportRepository implements ReportRepositoryInterface
{
    /**
     * اجمالي المبيعات (من غير طرح الإرجاعات)
     */
    public function getTotalSales()
    {
        return Sale::sum('total_price');
    }

    /**
     * اجمالي الإرجاعات (القيمة المالية)
     */
    public function getTotalReturns()
    {
        return MedicineReturn::join('sale_items', 'sale_items.id', '=', 'medicine_returns.sale_item_id')
            ->sum(DB::raw('medicine_returns.quantity_returned * sale_items.price'));
    }

    /**
     * صافي المبيعات = المبيعات - الإرجاعات
     */
    public function getNetSales()
    {
        return $this->getTotalSales() - $this->getTotalReturns();
    }

    /**
     * اجمالي المبيعات لليوم الحالي (صافي)
     */
    public function getDailyNetSales($date = null)
    {
        $date = $date ?? now()->toDateString();

        $totalSales = Sale::whereDate('sale_date', $date)->sum('total_price');

        $totalReturns = MedicineReturn::whereDate('returned_at', $date)
            ->join('sale_items', 'sale_items.id', '=', 'medicine_returns.sale_item_id')
            ->sum(DB::raw('medicine_returns.quantity_returned * sale_items.unit_price'));

        return $totalSales - $totalReturns;
    }

    /**
     * اجمالي المبيعات لشهر معين (صافي)
     */
    public function getMonthlyNetSales($year, $month)
    {
        $totalSales = Sale::whereYear('sale_date', $year)
            ->whereMonth('sale_date', $month)
            ->sum('total_price');

        $totalReturns = MedicineReturn::whereYear('returned_at', $year)
            ->whereMonth('returned_at', $month)
            ->join('sale_items', 'sale_items.id', '=', 'medicine_returns.sale_item_id')
            ->sum(DB::raw('medicine_returns.quantity_returned * sale_items.unit_price'));

        return $totalSales - $totalReturns;
    }
}
