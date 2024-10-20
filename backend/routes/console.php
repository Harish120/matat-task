<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->dailyAt('12:00');

Schedule::command('sync:orders')->everyMinute()
    ->onFailure(function () {
        Log::error('Failed to sync orders from Woocommerce.');
    })
    ->onSuccess(function () {
        Log::info('Orders synced successfully.');
    });
