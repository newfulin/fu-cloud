<?php

namespace App\Console;

use App\Console\Commands\SwooleCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CreateOrderCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SwooleCommand::class,
        CreateOrderCommand::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        Log::info('任务调度');
        $schedule->command('order:create')
            ->hourly()->between('8:00', '20:00');
    }
}
