<?php

namespace App\Jobs;

use App\Http\Controllers\AnalisisPredictivo\MantenimientoPredictivo;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalisisPredictivoPeriodico implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(MantenimientoPredictivo $analizador, NotificationService $notificationService)
    {
        // Realizar análisis predictivo
        $recomendaciones = $analizador->analizarPatrones();

        // Procesar recomendaciones
        foreach ($recomendaciones as $recomendacion) {
            // Crear mantenimientos preventivos si es necesario
            if ($recomendacion['riesgo'] === 'ALTO') {
                $mantenimiento = $analizador->generarMantenimientoPreventivo(
                    $recomendacion['infraestructura'],
                    'Mantenimiento Preventivo Urgente - Análisis Predictivo',
                    now()->addDays(7),
                    'alta'
                );

                // Notificar sobre el nuevo mantenimiento preventivo
                $notificationService->notificarMantenimientoProximo($mantenimiento);
            }
        }

        // Registrar resultados del análisis
        \Log::info('Análisis predictivo completado', [
            'total_recomendaciones' => count($recomendaciones),
            'fecha_analisis' => now()->toDateTimeString()
        ]);
    }
}
