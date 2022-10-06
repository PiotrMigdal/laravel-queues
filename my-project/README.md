## About queues

<ul>
<li>When job is dispatched, it adds record to the database</li>
<li>"Worker" handle this job and process them one by one</li>
<li>You can set timeout and for example that job is handled every 2 seconds until 1 minute</li>
<li>Worker can handle only one job as a time so if there is a timeout, it will retry the same job until reaches the limit</li>
<li>After worker reach maximum number of retries, it will mark job record as failed and go to another. Will not pick this up again</li>
<li>However, you can set how many workers as possible on your server</li>
<li>Worker default is process jobs from oldest to newest</li>
<li>You can set queue name and prioritize: php artisan queue:Work --queue=payments,default</li>
</ul>

## Setting up job

<ul>
<li>Create jobs table: php artisan queue:table && php artisan migrate</li>
<li>php artisan make:job SendWelcomeEmail</li>
<li>Check .env if QUEUE_CONNECTION=database</li>
</ul>

## Basics
my-project/app/Jobs/SendWelcomeEmail.php
<li>Dispatching jobs, $tries, $backoff, $timeout, $maxExceptions, retryUntil(), prioritizing</li>

## Workflows

