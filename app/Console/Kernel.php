<?php

namespace App\Console;

use App\Console\Commands\FinalizeAuctions;
use App\Console\Commands\RefundUsers;
use App\Console\Commands\StartAuctions;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{   
    protected $commands = [
        StartAuctions::class,
        RefundUsers::class,
        FinalizeAuctions::class,
    ];
    
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:start-auctions')->everyMinute();
        $schedule->command('app:refund-users')->everyMinute();
        $schedule->command('app:finalize-auctions')->everyMinute();
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
