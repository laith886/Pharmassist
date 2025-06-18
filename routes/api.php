<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\SaleItemController;
use App\Models\Medicine;
use App\Repositories\PurchaseItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//---------------------------------Medicine----------------------------------
Route::apiResource('medicines',MedicineController::class);
Route::get('ShowByName/{name}',[MedicineController::class,'ShowByName']);
Route::get('GetByCategoryName/{categoryName}',[MedicineController::class,'getMedicinesByCategoryName']);
//--------------------------------End Medicine-------------------------------


//--------------------------------Pharmacist---------------------------------
Route::post('RegisterPharmasict',[PharmacistController::class,'create']);
Route::post('Login',[PharmacistController::class,'login']);
Route::get('PharmacistProfile',[PharmacistController::class,'GetPharmacistProfile'])->middleware('auth:sanctum');
//-------------------------------End Pharmacist------------------------------


//--------------------------------SELL Medicine------------------------------
Route::post('SellMedicine',[SaleItemController::class,'Sell'])->middleware('auth:sanctum');
Route::get('GetPharmacistSales',[PharmacistController::class,'GetPharmacistSales']);
//--------------------------------END SELL-----------------------------------


//--------------------------------Request Supply-----------------------------
Route::post('SupplyRequest',[PurchaseItemController::class,'MakeSupplyOrder'])->middleware('auth:sanctum');
Route::get('GetPharmacistPurchase',[PharmacistController::class,'GetPharmacistPurchase']);
//--------------------------------End     Supply-----------------------------


//--------------------------------Categories-----------------------------
Route::get('GetAllCategories',[CategoryController::class,'index']);
