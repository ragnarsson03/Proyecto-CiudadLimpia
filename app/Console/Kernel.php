<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\AnalisisPredictivoPeriodico;

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
        // Ejecutar análisis predictivo cada domingo a las 00:00
        $schedule->job(new AnalisisPredictivoPeriodico)->weekly()->sundays()->at('00:00');
        
        // Verificar mantenimientos preventivos diariamente
        $schedule->command('mantenimiento:verificar')->dailyAt('06:00');
        
        // Verificar niveles de inventario diariamente
        $schedule->command('inventario:verificar')->dailyAt('07:00');
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
