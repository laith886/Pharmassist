<?php

use App\Models\Medicine;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', [DashboardController::class, 'index'])
        // احذفها لو ما بدك حماية تسجيل دخول
     ->name('dashboard');

Route::get('/dashboard/top-manufacturers', [DashboardController::class, 'topManufacturers'])
    ->name('dashboard.topManufacturers');
