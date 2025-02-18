<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rutas';

    protected $fillable = [
        'personal_id',
        'fecha',
        'ordenes_trabajo',
        'puntos',
        'distancia_total',
        'tiempo_estimado',
        'estado',
        'hora_inicio',
        'hora_fin',
        'metricas',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'ordenes_trabajo' => 'array',
        'puntos' => 'array',
        'metricas' => 'array',
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
        'distancia_total' => 'decimal:2'
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function ordenesTrabajoRelacionadas()
    {
        return $this->hasMany(OrdenTrabajo::class);
    }

    public function iniciarRuta()
    {
        if ($this->estado === 'pendiente') {
            $this->estado = 'en_proceso';
            $this->hora_inicio = now();
            $this->save();
            return true;
        }
        return false;
    }

    public function finalizarRuta()
    {
        if ($this->estado === 'en_proceso') {
            $this->estado = 'completada';
            $this->hora_fin = now();
            $this->calcularMetricas();
            $this->save();
            return true;
        }
        return false;
    }

    protected function calcularMetricas()
    {
        $duracionReal = $this->hora_fin->diffInMinutes($this->hora_inicio);
        $this->metricas = array_merge($this->metricas ?? [], [
            'duracion_real' => $duracionReal,
            'eficiencia' => $this->tiempo_estimado > 0 
                ? ($this->tiempo_estimado / $duracionReal) * 100 
                : 0
        ]);
    }

    public function optimizarRuta()
    {
        // Implementar algoritmo de optimización (por ejemplo, TSP)
        $puntos = collect($this->puntos);
        
        // Ejemplo básico: ordenar por proximidad al punto inicial
        $puntoInicial = $puntos->first();
        $rutaOptimizada = [$puntoInicial];
        $puntosRestantes = $puntos->slice(1);
        
        while ($puntosRestantes->isNotEmpty()) {
            $ultimoPunto = end($rutaOptimizada);
            $siguiente = $this->encontrarPuntoMasCercano($ultimoPunto, $puntosRestantes);
            $rutaOptimizada[] = $siguiente;
            $puntosRestantes = $puntosRestantes->filter(fn($p) => $p !== $siguiente);
        }
        
        $this->puntos = $rutaOptimizada;
        $this->save();
    }

    protected function encontrarPuntoMasCercano($punto, $puntos)
    {
        return $puntos->reduce(function ($cercano, $actual) use ($punto) {
            if (!$cercano) return $actual;
            
            $distanciaActual = $this->calcularDistancia($punto, $actual);
            $distanciaCercano = $this->calcularDistancia($punto, $cercano);
            
            return $distanciaActual < $distanciaCercano ? $actual : $cercano;
        });
    }

    protected function calcularDistancia($punto1, $punto2)
    {
        // Implementar cálculo de distancia (por ejemplo, fórmula de Haversine)
        $lat1 = $punto1['lat'];
        $lon1 = $punto1['lng'];
        $lat2 = $punto2['lat'];
        $lon2 = $punto2['lng'];
        
        $r = 6371; // Radio de la Tierra en km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $r * $c;
    }
}
