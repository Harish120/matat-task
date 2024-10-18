<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Services\TaskScheduler;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot(Schedule $schedule)
    {
        TaskScheduler::schedule($schedule);
    }
}
