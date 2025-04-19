<?php

use App\Jobs\CheckForExpiredSubscriptions;
use App\Jobs\SendSubscriptionExpirationWarning;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(SendSubscriptionExpirationWarning::class)
    ->dailyAt('23:59')
    ->description('Send subscription expiration warning emails');

Schedule::job(CheckForExpiredSubscriptions::class)
    ->dailyAt('23:59')
    ->description('Send subscription expiration warning emails');
