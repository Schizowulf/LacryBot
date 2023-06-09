<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }
    
    protected function shortSchedule(\Spatie\ShortSchedule\ShortSchedule $shortSchedule)
    {
        // this artisan command will run every second
        $shortSchedule->command('test_s')->everySecond(2)->withoutOverlapping();
        
        // this artisan command will run every second, its signature will be resolved from container
        //$shortSchedule->command(\Spatie\ShortSchedule\Tests\Unit\TestCommand::class)->everySecond();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
