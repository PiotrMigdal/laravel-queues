<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Set timeout variable - it is build in variable which will fail job after in this case 1s timeout
//    public $timeout = 1;

    // Set how many times it tries
    public $tries = 10;

    // specify maximum number of exceptions. So it $tries=10 but only 2 of them can be because of exception until it failes.
    public $maxExceptions = 2;

    // it works for retryUntil(). Rather than every millisecond, system will retry every 2 seconds
//    public $backoff = 2;
    // If you add array it will wait 2 sec after first, 10 after second, 20 seconds after other until reach all $tries
//    public $backoff = [2, 10, 20];



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
//        throw new \Exception(('Failed'));
        sleep(3);

        // Relese the job back after 2 second - just for testing
        $this->release(2);
    }

    // This will override retry and will retry until 1 minute, unlimited number of times (as many times as possible - every millisecond or so)
//    public function retryUntil(): \Illuminate\Support\Carbon
//    {
//        return now()->addMinute();
//    }
}
