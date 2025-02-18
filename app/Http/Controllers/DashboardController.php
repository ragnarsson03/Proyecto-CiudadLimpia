<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Infraestructura;
use App\Models\MantenimientoPreventivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        // Filtros con valores por defecto
        $filtroEstado = $request->input('estado', 'todos');
        $filtroInfraestructura = $request->input('infraestructura', 'todos');
        $filtroPeriodo = $request->input('periodo', '30');

        // Query base para incidencias
        $incidenciasQuery = Incidencia::query()
            ->with(['infraestructura', 'tecnico', 'ciudadano'])
            ->when($filtroEstado !== 'todos', function($query) use ($filtroEstado) {
                return $query->where('incidencias.estado', $filtroEstado);
            })
            ->when($filtroInfraestructura !== 'todos', function($query) use ($filtroInfraestructura) {
                return $query->where('incidencias.infraestructura_id', $filtroInfraestructura);
            })
            ->when($filtroPeriodo, function($query) use ($filtroPeriodo) {
                return $query->where('incidencias.fecha', '>=', now()->subDays($filtroPeriodo));
            });

        // Estadísticas de Infraestructura
        $totalInfraestructuras = Infraestructura::count();
        $infraestructurasOperativas = Infraestructura::where('infraestructuras.estado', 'operativo')->count();
        $infraestructurasMantenimiento = Infraestructura::where('infraestructuras.estado', 'mantenimiento')->count();
        $infraestructurasFueraServicio = Infraestructura::where('infraestructuras.estado', 'fuera_de_servicio')->count();

        // Infraestructuras más afectadas
        $infraestructurasMasAfectadas = DB::table('infraestructuras')
            ->select('infraestructuras.*')
            ->selectRaw('COUNT(incidencias.id) as total_incidencias')
            ->leftJoin('incidencias', 'infraestructuras.id', '=', 'incidencias.infraestructura_id')
            ->where('incidencias.fecha', '>=', now()->subDays($filtroPeriodo))
            ->groupBy('infraestructuras.id')
            ->havingRaw('COUNT(incidencias.id) > 0')
            ->orderByRaw('COUNT(incidencias.id) DESC')
            ->take(5)
            ->get();

        // Estadísticas generales de incidencias
        $totalIncidencias = $incidenciasQuery->count();
        $incidenciasHoy = $incidenciasQuery->clone()->whereDate('incidencias.fecha', today())->count();

        // Estadísticas por estado de incidencias
        $incidenciasPorEstado = $incidenciasQuery->clone()
            ->select('incidencias.estado', DB::raw('count(*) as total'))
            ->groupBy('incidencias.estado')
            ->pluck('total', 'estado')
            ->toArray();

        // Estadísticas por tipo de infraestructura
        $incidenciasPorTipo = DB::table('incidencias')
            ->join('infraestructuras', 'incidencias.infraestructura_id', '=', 'infraestructuras.id')
            ->select(DB::raw('infraestructuras.tipo'), DB::raw('count(*) as total'))
            ->when($filtroEstado !== 'todos', function($query) use ($filtroEstado) {
                return $query->where('incidencias.estado', $filtroEstado);
            })
            ->when($filtroInfraestructura !== 'todos', function($query) use ($filtroInfraestructura) {
                return $query->where('incidencias.infraestructura_id', $filtroInfraestructura);
            })
            ->when($filtroPeriodo, function($query) use ($filtroPeriodo) {
                return $query->where('incidencias.fecha', '>=', now()->subDays($filtroPeriodo));
            })
            ->groupBy('infraestructuras.tipo')
            ->pluck('total', 'tipo')
            ->toArray();

        // Últimas incidencias
        $ultimasIncidencias = $incidenciasQuery->clone()
            ->latest('incidencias.fecha')
            ->take(5)
            ->get();

        // Últimas infraestructuras actualizadas
        $ultimasInfraestructuras = Infraestructura::latest('infraestructuras.updated_at')
            ->take(5)
            ->get();

        // Estadísticas de Mantenimiento Preventivo
        $mantenimientosPendientes = MantenimientoPreventivo::where('proxima_ejecucion', '<=', now())
            ->where('activo', true)
            ->count();
            
        $mantenimientosProximos = MantenimientoPreventivo::where('proxima_ejecucion', '>', now())
            ->where('proxima_ejecucion', '<=', now()->addDays(7))
            ->where('activo', true)
            ->count();
            
        $costoMantenimientoMensual = MantenimientoPreventivo::where('activo', true)
            ->where('proxima_ejecucion', '>=', now()->startOfMonth())
            ->where('proxima_ejecucion', '<=', now()->endOfMonth())
            ->sum('costo_estimado');

        // Lista de infraestructuras para el filtro
        $infraestructuras = Infraestructura::select('infraestructuras.id', 'infraestructuras.tipo', 'infraestructuras.ubicacion')->get();

        return view('dashboard', compact(
            'totalInfraestructuras',
            'infraestructurasOperativas',
            'infraestructurasMantenimiento',
            'infraestructurasFueraServicio',
            'infraestructurasMasAfectadas',
            'totalIncidencias',
            'incidenciasHoy',
            'incidenciasPorEstado',
            'incidenciasPorTipo',
            'ultimasIncidencias',
            'ultimasInfraestructuras',
            'mantenimientosPendientes',
            'mantenimientosProximos',
            'costoMantenimientoMensual',
            'infraestructuras',
            'filtroEstado',
            'filtroInfraestructura',
            'filtroPeriodo'
        ));
    }
}
