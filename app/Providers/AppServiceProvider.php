<?php

namespace App\Providers;

use App\Repositories\Interfaces\ManufactururRepositoryInterface;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use App\Repositories\Interfaces\PharmacistRepositoryInterface;
use App\Repositories\Interfaces\SaleItemRepositoryInterface;
use App\Repositories\ManufacturerRepository;
use App\Repositories\MedicineRepository;
use App\Repositories\PharmacistRepository;
use App\Repositories\SaleItemRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(MedicineRepositoryInterface::class, MedicineRepository::class);
        $this->app->bind(SaleItemRepositoryInterface::class, SaleItemRepository::class);
        $this->app->bind(PharmacistRepositoryInterface::class, PharmacistRepository::class);
        $this->app->bind(ManufactururRepositoryInterface::class,ManufacturerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
