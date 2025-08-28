<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DashboardRepositoryInterface;

class DashboardController extends Controller
{
    public function __construct(private DashboardRepositoryInterface $repo) {}

    public function index()
    {
        $summary      = $this->repo->getTodaySummary();
        $lowStock     = $this->repo->getLowStock(10);
        $expiringSoon = $this->repo->getExpiringSoon(30, 10);
        $topMedicines = $this->repo->getTopMedicines(30, 5);
        $trend        = $this->repo->getSalesTrend(7);

        return view('dashboard.index', [
            'salesTodayCount'  => $summary['sales_count'],
            'salesTodayAmount' => $summary['sales_amount'],
            'returnsTodayQty'  => $summary['returns_qty'],
            'purchasesToday'   => $summary['purchases_cnt'],
            'lowStock'         => $lowStock,
            'expiringSoon'     => $expiringSoon,
            'topMedicines'     => $topMedicines,
            'trendLabels'      => $trend['labels'],
            'trendValues'      => $trend['values'],
        ]);
    }
}
