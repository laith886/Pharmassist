<?php

namespace App\Repositories\Interfaces;

interface DashboardRepositoryInterface
{
    public function getTodaySummary(): array;
    public function getLowStock(int $limit = 10);
    public function getExpiringSoon(int $days = 30, int $limit = 10);
    public function getTopMedicines(int $days = 30, int $limit = 5);
    public function getSalesTrend(int $days = 7): array;
    public function getTopManufacturers(int $limit = 6);
}
