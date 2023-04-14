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

        $schedule->command('clever:locations')->dailyAt('08:00');
        $schedule->command('clever:locations')->dailyAt('18:00');
        $schedule->command('clever:chargers')->everyMinute();
        // $schedule->command('clever:chargers')
        //     ->everyMinute()
        //     ->timezone('Europe/Copenhagen')
        //     ->between('09:30', '10:15');
        // $schedule->command('clever:chargers')
        //     ->everyMinute()
        //     ->timezone('Europe/Copenhagen')
        //     ->between('18:30', '19:15');

        // $schedule->command('clever:chargers')
        //     ->hourly()
        //     ->timezone('Europe/Copenhagen')
        //     ->unlessBetween('09:30', '10:15')
        //     ->unlessBetween('18:30', '19:15');
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
