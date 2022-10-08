<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Deploy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // You can use lock to avoid situation when 2 workers access the same resource at the same time
        // In this example, info is being saved in laravel.log
        // Without lock, if 2 workers are running, they will access the file at the same time:
//        [2022-10-08 14:39:37] local.INFO: Started Deploying...
//        [2022-10-08 14:39:38] local.INFO: Started Deploying...
//        [2022-10-08 14:39:40] local.INFO: Finished Deploying...
//        [2022-10-08 14:39:41] local.INFO: Finished Deploying...
        // If lock is added, this will wait until worker 1 is done with file, then worker 2 starts:
//        [2022-10-08 14:40:17] local.INFO: Started Deploying...
//        [2022-10-08 14:40:20] local.INFO: Finished Deploying...
//        [2022-10-08 14:40:20] local.INFO: Started Deploying...
//        [2022-10-08 14:40:23] local.INFO: Finished Deploying...
        // Block means that, when one job finish, it will block file for 10 seconds until another worker can do
        Cache::lock('deployments')->block(10, function() {
            info('Started Deploying...');
            sleep(3);
            info('Finished Deploying...');
        });

        // Similar to cache is funnel
        // In funnel, you can limit how many workers can access the file at the same time
        // funnel - lejek
        Redis::funnel('deployments')
            ->limit(5)
            ->block(10)
            ->then(function () {
                info('Started Deploying...');
                sleep(3);
                info('Finished Deploying...');
            });

        // Throttle is another limitation
        // It can allow for example 10 instance of this job to run every 60 seconds
        Redis::throttle('deployments')
            ->allow(10)
            ->every(60)
            ->block(10)
            ->then(function () {
                info('Started Deploying...');
                sleep(3);
                info('Finished Deploying...');
            });

    }

    // Middleware is working like other mifflewars in laravel
    // In this case it is similar to Cache::lock. It will control that only one instance can be ran at the same time
    // And when it has ran, wait 10 s before another
    // Only difference is that it won't lock the job but put all incoming jobs back to the queue
    public function middleware()
    {
        return [
            new WithoutOverlapping('deployments', 10)
        ];
    }
}
