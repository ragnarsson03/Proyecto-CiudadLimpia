<?php

namespace App\Http\Controllers\AnalisisPredictivo;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use App\Models\Infraestructura;
use App\Models\MantenimientoPreventivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MantenimientoPredictivo extends Controller
{
    public function analizarPatrones()
    {
        // Obtener datos históricos de incidencias
        $incidenciasHistoricas = Incidencia::with(['infraestructura'])
            ->where('created_at', '>=', now()->subYear())
            ->get()
            ->groupBy('infraestructura_id');

        $predicciones = [];

        foreach ($incidenciasHistoricas as $infraestructuraId => $incidencias) {
            // Calcular frecuencia de incidencias
            $frecuenciaIncidencias = $incidencias->count() / 12; // Promedio mensual

            // Calcular tiempo promedio entre incidencias
            $tiemposEntreIncidencias = [];
            $incidenciasOrdenadas = $incidencias->sortBy('fecha');
            
            for ($i = 1; $i < $incidenciasOrdenadas->count(); $i++) {
                $tiemposEntreIncidencias[] = $incidenciasOrdenadas[$i]->fecha->diffInDays($incidenciasOrdenadas[$i-1]->fecha);
            }

            $tiempoPromedio = count($tiemposEntreIncidencias) > 0 
                ? array_sum($tiemposEntreIncidencias) / count($tiemposEntreIncidencias)
                : 0;

            // Analizar tipos de incidencias más comunes
            $tiposIncidencias = $incidencias->groupBy('tipo')
                ->map(function ($grupo) {
                    return $grupo->count();
                })
                ->sortDesc();

            // Calcular severidad promedio
            $severidadPromedio = $incidencias->avg(function ($incidencia) {
                switch ($incidencia->prioridad) {
                    case 'baja': return 1;
                    case 'media': return 2;
                    case 'alta': return 3;
                    case 'critica': return 4;
                    default: return 0;
                }
            });

            // Generar predicción
            $predicciones[$infraestructuraId] = [
                'infraestructura_id' => $infraestructuraId,
                'frecuencia_mensual' => $frecuenciaIncidencias,
                'tiempo_promedio_entre_incidencias' => $tiempoPromedio,
                'tipos_comunes' => $tiposIncidencias->take(3),
                'severidad_promedio' => $severidadPromedio,
                'proxima_incidencia_estimada' => $this->estimarProximaIncidencia(
                    $incidenciasOrdenadas->last()->fecha,
                    $tiempoPromedio
                ),
                'riesgo' => $this->calcularNivelRiesgo($frecuenciaIncidencias, $severidadPromedio)
            ];
        }

        return $this->generarRecomendaciones($predicciones);
    }

    private function estimarProximaIncidencia($ultimaFecha, $tiempoPromedio)
    {
        return $ultimaFecha->addDays(round($tiempoPromedio));
    }

    private function calcularNivelRiesgo($frecuencia, $severidad)
    {
        $indiceRiesgo = $frecuencia * $severidad;

        if ($indiceRiesgo >= 8) return 'ALTO';
        if ($indiceRiesgo >= 4) return 'MEDIO';
        return 'BAJO';
    }

    private function generarRecomendaciones($predicciones)
    {
        $recomendaciones = [];

        foreach ($predicciones as $infraestructuraId => $prediccion) {
            $infraestructura = Infraestructura::find($infraestructuraId);
            
            if ($prediccion['riesgo'] === 'ALTO') {
                // Generar mantenimiento preventivo urgente
                $this->generarMantenimientoPreventivo(
                    $infraestructura,
                    'Mantenimiento Preventivo Urgente',
                    now()->addDays(7),
                    'alta'
                );

                $recomendaciones[] = [
                    'infraestructura' => $infraestructura->tipo . ' en ' . $infraestructura->ubicacion,
                    'recomendacion' => 'Programar mantenimiento preventivo urgente',
                    'justificacion' => 'Alto riesgo de falla basado en histórico de incidencias',
                    'fecha_sugerida' => now()->addDays(7)->format('Y-m-d'),
                    'acciones' => [
                        'Inspección completa del sistema',
                        'Reemplazo de componentes críticos',
                        'Actualización de documentación técnica'
                    ]
                ];
            }
        }

        return $recomendaciones;
    }

    private function generarMantenimientoPreventivo($infraestructura, $nombre, $fecha, $prioridad)
    {
        return MantenimientoPreventivo::create([
            'infraestructura_id' => $infraestructura->id,
            'nombre' => $nombre,
            'descripcion' => 'Mantenimiento preventivo generado por análisis predictivo',
            'frecuencia' => 'unica',
            'dias_frecuencia' => 0,
            'proxima_ejecucion' => $fecha,
            'duracion_estimada' => 240, // 4 horas por defecto
            'prioridad' => $prioridad,
            'activo' => true
        ]);
    }
}
