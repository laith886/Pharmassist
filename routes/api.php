<?php

use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\SaleItemController;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//---------------------------------Medicine--------------------------------
Route::apiResource('medicines',MedicineController::class);
Route::get('ShowByName/{name}',[MedicineController::class,'ShowByName']);
Route::get('GetByCategoryName/{categoryName}',[MedicineController::class,'getMedicinesByCategoryName']);
//--------------------------------End Medicine--------------------------------

//--------------------------------Pharmacist----------------------------------
Route::post('RegisterPharmasict',[PharmacistController::class,'create']);
Route::post('Login',[PharmacistController::class,'login']);
//-------------------------------End Pharmacist--------------------------------

//--------------------------------SELL Medicine------------------------------
Route::post('SellMedicine',[SaleItemController::class,'Sell'])->middleware('auth:sanctum');
//--------------------------------END SELL-----------------------------------
