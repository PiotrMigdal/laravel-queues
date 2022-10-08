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
my-project/app/Jobs/SendWelcomeEmail.php<br>
web.php uri: /sendWelcome
<li>Dispatching jobs, $tries, $backoff, $timeout, $maxExceptions, retryUntil(), prioritizing</li>

## Workflows
<p>There are chains and batches</p>
<p>For chains see web.php /chain /chain2 route</p>
<p>For batches see /batches /batches2 in web.php. Batches require additional settings:</p>
<li>- use Batchable in job, see PullRepo</li>
<li>- create batches table php artisan queue:batches-table && php artisan migrate</li>

## Controlling and limiting jobs
<p>Sometimes you need control the worker to for example don't overuse resource.</p>
<p>It can be useful if you write something to file or use SFTP, you don't want to put to many jobs at the same time</p>
<p>You can use cache, funnel, throttle or method middleware()</p>
<p>See Deploy.php</p>

## Unique
<p>If the job extends ShouldBeUnique class, it avoid creating multiple jobs of the same instance</p>
<p>See DeployUnique and /unique route. You can trigger job many times but it will create only one record in jobs database table</p>
<p>ShouldBeUnique - unique until finish processing</p>
<p>ShouldBeUniqueUntilProcessing - unique until starts processing. So when current job starts, it can pick up another instance</p>

## Throttle exception middleware
<p>You can configure that when job fails 10 times in the row, it will stop reaching the instances</p>
<p>Can be useful if we use third party service that is for example down.</p>
<p>see SendBySFTP.php</p>

## 
