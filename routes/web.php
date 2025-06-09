<?php

use App\Models\Medicine;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-medicine', function () {
    return Medicine::find(1);
});
Route::get('/mail', function () {


    Mail::raw('Hello! This is a test email from Laravel.', function ($message) {
        $message->to('layth.younes10@gmail.com')
                ->subject('Test Email');
    });

    return 'Test email sent!';
});
