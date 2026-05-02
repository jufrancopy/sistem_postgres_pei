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
        \App\Console\Commands\SiessGenerarPeriodosCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // SIESS: Silencio administrativo — Art. 8 Res. 266/2022
        $schedule->job(new \App\Jobs\SiessAprobarPorSilencioJob)
                 ->dailyAt('07:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/siess_silencio.log'));

        // SIESS: Alertas de vencimiento próximo (1-2 días hábiles)
        $schedule->job(new \App\Jobs\SiessAlertarVencimientoJob)
                 ->dailyAt('08:00')
                 ->withoutOverlapping();

        // SIESS: Generar período del mes siguiente el último día de cada mes
        $schedule->command('siess:generar-periodos --desde=' . now()->year . ' --hasta=' . now()->addYear()->year)
                 ->monthlyOn(28, '23:00')
                 ->withoutOverlapping();
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
