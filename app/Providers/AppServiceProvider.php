<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use App\Repositories\Interfaces\PharmacistRepositoryInterface;
use App\Repositories\Interfaces\PurchaseItemsRepositoryInterface;
use App\Repositories\Interfaces\SaleItemRepositoryInterface;
use App\Repositories\Interfaces\MedicineReturnRepositoryInterface;
use App\Repositories\MedicineReturnRepository;
use App\Repositories\MedicineRepository;
use App\Repositories\PharmacistRepository;
use App\Repositories\PurchaseItemRepository;
use App\Repositories\SaleItemRepository;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Repositories\DashboardRepository;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use App\Repositories\ReportRepository;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(MedicineRepositoryInterface::class, MedicineRepository::class);
        $this->app->bind(SaleItemRepositoryInterface::class, SaleItemRepository::class);
        $this->app->bind(PharmacistRepositoryInterface::class, PharmacistRepository::class);
        $this->app->bind(PurchaseItemsRepositoryInterface::class,PurchaseItemRepository::class);
        $this->app->bind(MedicineReturnRepositoryInterface::class, MedicineReturnRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);

    }
    public function boot(): void
    {
        //
    }
}
