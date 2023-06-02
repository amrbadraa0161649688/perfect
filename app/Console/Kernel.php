<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\EmployeeAttachmentsEnd::class,
        Commands\EmployeeContractEnd::class,
        Commands\WaybillCarArrival::class,


    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('files:expire')->daily();
//        $schedule->exec("files:expire");
//        $schedule->exec("contract:end");
//        $schedule->exec("waybills:arrive");

        $schedule->command('files:expire')->daily()->at('11:10');
        $schedule->command('contract:end')->daily()->at('11:10');
        $schedule->command('waybills:arrive')->daily()->at('11:10');
        $schedule->command('trans:cron')->timezone('Asia/Riyadh')->everyThirtyMinutes();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
