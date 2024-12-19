<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:email-users-about-new-locations')->dailyAt('12:00');
        $schedule->command('app:email-users-about-new-chargers')->dailyAt('13:00');

        $schedule->command('clever:firestore 100')->dailyAt('08:05');
        $schedule->command('clever:chargers')->dailyAt('4:20');

        $schedule->command('location:history')->everyFifteenMinutes();

        $schedule->command('model:prune')->hourlyAt(11);

        $schedule->command('horizon:snapshot')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
