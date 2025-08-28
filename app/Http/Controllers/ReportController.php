<?php

namespace App\Http\Controllers;

use App\Repositories\ReportRepository;
use App\Repositories\Interfaces\ReportRepositoryInterface;
class ReportController extends Controller
{
    protected $reportRepo;

   public function __construct(ReportRepositoryInterface $reportRepo)
{
    $this->reportRepo = $reportRepo;
}
    public function netSales()
    {
        return response()->json([
            'net_sales' => $this->reportRepo->getNetSales()
        ]);
    }

    public function dailyNetSales($date = null)
    {
        return response()->json([
            'date' => $date ?? now()->toDateString(),
            'net_sales' => $this->reportRepo->getDailyNetSales($date)
        ]);
    }

    public function monthlyNetSales($year, $month)
    {
        return response()->json([
            'year' => $year,
            'month' => $month,
            'net_sales' => $this->reportRepo->getMonthlyNetSales($year, $month)
        ]);
    }
}
