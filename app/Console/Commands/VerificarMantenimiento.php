<?php

namespace App\Console\Commands;

use App\Models\MantenimientoPreventivo;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class VerificarMantenimiento extends Command
{
    protected $signature = 'mantenimiento:verificar';
    protected $description = 'Verifica los mantenimientos preventivos programados';

    public function handle(NotificationService $notificationService)
    {
        $this->info('Verificando mantenimientos preventivos...');

        // Obtener mantenimientos próximos (7 días)
        $mantenimientosProximos = MantenimientoPreventivo::where('proxima_ejecucion', '<=', now()->addDays(7))
            ->where('proxima_ejecucion', '>=', now())
            ->where('activo', true)
            ->get();

        foreach ($mantenimientosProximos as $mantenimiento) {
            $this->info("Notificando mantenimiento ID: {$mantenimiento->id}");
            $notificationService->notificarMantenimientoProximo($mantenimiento);
        }

        $this->info("Se encontraron {$mantenimientosProximos->count()} mantenimientos próximos");
        return Command::SUCCESS;
    }
}
