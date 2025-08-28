<?php

namespace App\Repositories\Interfaces;

interface ReportRepositoryInterface 
{
    public function getTotalSales();
    public function getTotalReturns();
    public function getNetSales();
    public function getDailyNetSales($date = null);
    public function getMonthlyNetSales($year, $month);
}
