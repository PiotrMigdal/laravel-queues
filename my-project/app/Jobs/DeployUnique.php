<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

// ShouldBeUnique - unique until finish processing
// ShouldBeUniqueUntilProcessing - unique until starts processing. So when current job starts, it can pick up another instance
class DeployUnique implements ShouldQueue, ShouldBeUnique
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
        info('Started Deploying...');
        sleep(3);
        info('Finished Deploying...');

    }

    //optional
    public function uniqueId()
    {
        return 'deployments';
    }

    // optional
    // How long this can be unique. Just in case job is stuck
    public function uniqueFor()
    {
        return 60;
    }
}
