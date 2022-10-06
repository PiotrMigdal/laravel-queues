<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/sendWelcome', function () {
//    (new \App\Jobs\SendWelcomeEmail())->handle();

    // make 100 jobs
//    foreach (range(1, 100) as $i) {
//        \App\Jobs\SendWelcomeEmail::dispatch();
//    }


    \App\Jobs\SendWelcomeEmail::dispatch();

    // add que name it can be used like php artisan queue:Work --queue=payments,default
//    \App\Jobs\ProcessPayment::dispatch()->onQueue('payments');
    return view('welcome');
});
