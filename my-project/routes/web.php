<?php

use App\Jobs\PullRepo;
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

// Chain workflows depends on each other
// If one fails, it stops execution
Route::get('/chain', function () {
    $chain = [
        new PullRepo(),
        new \App\Jobs\RunTest(),
        new \App\Jobs\Deploy()
    ];
    \Illuminate\Support\Facades\Bus::chain($chain)->dispatch();
    return view('welcome');
});

//batch
Route::get('/batch', function () {
    $batch = [
        new PullRepo('laracast/project1'),
        new PullRepo('laracast/project2'),
        new PullRepo('laracast/project3')
    ];
    \Illuminate\Support\Facades\Bus::batch($batch)
        // allow failures will ignore if any element failed and continue to the next
        // will not mark job as cancelled
        ->allowFailures()
        // catch errors if any job failed
        ->catch(function ($batch, $e) {
         //
        })
        // run code if all jobs successful
        ->then(function ($batch) {
            //
        })
        // run when ends successful or failure
        ->finally(function ($batch) {
            //
        })
        // name of queue
        ->onQueue('deployments')
        ->onConnection('database')
        ->dispatch();
    return view('welcome');
});

// batch of chains
Route::get('/batch2', function () {
    $batch = [
        [
            new PullRepo('laracast/project1'),
            new \App\Jobs\RunTest('laracast/project1'),
            new \App\Jobs\Deploy('laracast/project1')
        ],
        [
            new PullRepo('laracast/project2'),
            new \App\Jobs\RunTest('laracast/project2'),
            new \App\Jobs\Deploy('laracast/project2')
        ]
    ];
    \Illuminate\Support\Facades\Bus::batch($batch)
        ->allowFailures()
        ->dispatch();
    return view('welcome');
});

// chain of batches
Route::get('/chain2', function () {
    // you can include cbatch in chain etc...
    \Illuminate\Support\Facades\Bus::chain([
        new \App\Jobs\Deploy(),
        function () {
            \Illuminate\Support\Facades\Bus::batch([
                new \App\Jobs\RunTest('laracast/project2'),
                new \App\Jobs\Deploy('laracast/project2')
            ])->dispatch();
        }]
    )->dispatch();
    return view('welcome');
});
