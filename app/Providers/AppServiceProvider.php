<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use App\Repositories\Interfaces\PharmacistRepositoryInterface;
use App\Repositories\Interfaces\PurchaseItemsRepositoryInterface;
use App\Repositories\Interfaces\SaleItemRepositoryInterface;
use App\Repositories\Interfaces\SaleItemsRepositoryInterface;
use App\Repositories\MedicineRepository;
use App\Repositories\PharmacistRepository;
use App\Repositories\PurchaseItemRepository;
use App\Repositories\SaleItemRepository;
use App\Repositories\SaleItemsRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(MedicineRepositoryInterface::class, MedicineRepository::class);
        $this->app->bind(SaleItemRepositoryInterface::class, SaleItemRepository::class);
        $this->app->bind(PharmacistRepositoryInterface::class, PharmacistRepository::class);
        $this->app->bind(PurchaseItemsRepositoryInterface::class,PurchaseItemRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
    }
    public function boot(): void
    {
        //
    }
}
