<?php

use App\Models\Medicine;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PurchaseItemController;

Route::get('/', function () {
    return view('welcome');
});
