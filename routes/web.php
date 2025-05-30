<?php

use App\Models\Medicine;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-medicine', function () {
    return Medicine::find(1);
});
