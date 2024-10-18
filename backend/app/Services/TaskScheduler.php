<?php
namespace App\Services;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;

class TaskScheduler
{
    /**
     * Schedule all recurring tasks.
     */
    public static function schedule(Schedule $schedule)
    {
        // Schedule the sync:orders command to run daily at 12 PM
        $schedule->command('sync:orders')->dailyAt('12:00')
            ->onFailure(function () {
                Log::error('Failed to sync orders from Woocommerce.');
            })
            ->onSuccess(function () {
                Log::info('Orders synced successfully.');
            });
    }
}
