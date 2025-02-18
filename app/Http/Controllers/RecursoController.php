<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\Material;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecursoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function indexPersonal()
    {
        $personal = Personal::with(['ordenesTrabajoAsignadas'])
            ->withCount(['ordenesTrabajoAsignadas' => function($query) {
                $query->where('estado', 'en_proceso');
            }])
            ->get();

        return view('recursos.personal.index', compact('personal'));
    }

    public function indexMateriales()
    {
        $materiales = Material::withSum('ordenesTrabajoAsignadas', 'cantidad_usada')
            ->withCount('ordenesTrabajoAsignadas')
            ->get();

        $materialesBajoStock = Material::where('cantidad', '<=', DB::raw('stock_minimo'))
            ->get();

        return view('recursos.materiales.index', compact('materiales', 'materialesBajoStock'));
    }

    public function asignarRecursos(Request $request, OrdenTrabajo $ordenTrabajo)
    {
        $request->validate([
            'personal_ids' => 'required|array',
            'personal_ids.*' => 'exists:personal,id',
            'materiales' => 'required|array',
            'materiales.*.id' => 'exists:materiales,id',
            'materiales.*.cantidad' => 'required|numeric|min:0'
        ]);

        DB::transaction(function() use ($request, $ordenTrabajo) {
            // Asignar personal
            $ordenTrabajo->personal()->sync($request->personal_ids);

            // Asignar materiales
            foreach ($request->materiales as $material) {
                $ordenTrabajo->materiales()->attach($material['id'], [
                    'cantidad_asignada' => $material['cantidad']
                ]);

                // Actualizar stock
                Material::find($material['id'])->decrement('cantidad', $material['cantidad']);
            }

            $ordenTrabajo->update(['estado' => 'en_proceso']);
        });

        return redirect()->back()->with('success', 'Recursos asignados correctamente');
    }

    public function optimizarRutas()
    {
        $ordenesDelDia = OrdenTrabajo::where('fecha_programada', today())
            ->where('estado', 'pendiente')
            ->with(['infraestructura'])
            ->get();

        // Implementar algoritmo de optimización de rutas
        $rutasOptimizadas = $this->calcularRutasOptimas($ordenesDelDia);

        return view('recursos.rutas.optimizacion', compact('rutasOptimizadas'));
    }

    private function calcularRutasOptimas($ordenes)
    {
        // Implementación básica del algoritmo del vecino más cercano
        $rutas = collect();
        $ordenesRestantes = $ordenes->toArray();
        
        while (!empty($ordenesRestantes)) {
            $ruta = [];
            $actual = array_shift($ordenesRestantes);
            $ruta[] = $actual;
            
            while (!empty($ordenesRestantes)) {
                $masCercano = $this->encontrarMasCercano($actual, $ordenesRestantes);
                if (!$masCercano) break;
                
                $ruta[] = $masCercano;
                $actual = $masCercano;
                
                // Remover la orden más cercana de las órdenes restantes
                $ordenesRestantes = array_filter($ordenesRestantes, function($orden) use ($masCercano) {
                    return $orden['id'] !== $masCercano['id'];
                });
            }
            
            $rutas->push($ruta);
        }
        
        return $rutas;
    }

    private function encontrarMasCercano($actual, $ordenes)
    {
        if (empty($ordenes)) return null;
        
        $masCercano = null;
        $distanciaMinima = PHP_FLOAT_MAX;
        
        foreach ($ordenes as $orden) {
            $distancia = $this->calcularDistancia(
                $actual['infraestructura']['latitud'],
                $actual['infraestructura']['longitud'],
                $orden['infraestructura']['latitud'],
                $orden['infraestructura']['longitud']
            );
            
            if ($distancia < $distanciaMinima) {
                $distanciaMinima = $distancia;
                $masCercano = $orden;
            }
        }
        
        return $masCercano;
    }

    private function calcularDistancia($lat1, $lon1, $lat2, $lon2)
    {
        // Fórmula de Haversine para calcular distancia entre dos puntos geográficos
        $earthRadius = 6371; // Radio de la Tierra en kilómetros

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
}
