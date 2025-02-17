<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Infraestructura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $filtroEstado = $request->get('estado', 'todos');
        $filtroInfraestructura = $request->get('infraestructura', 'todos');
        $filtroPeriodo = $request->get('periodo', '30'); // días

        // Query base para incidencias
        $incidenciasQuery = Incidencia::query()
            ->with(['infraestructura', 'tecnico', 'ciudadano'])
            ->when($filtroEstado !== 'todos', function($query) use ($filtroEstado) {
                return $query->where('estado', $filtroEstado);
            })
            ->when($filtroInfraestructura !== 'todos', function($query) use ($filtroInfraestructura) {
                return $query->where('infraestructura_id', $filtroInfraestructura);
            })
            ->when($filtroPeriodo, function($query) use ($filtroPeriodo) {
                return $query->where('fecha', '>=', now()->subDays($filtroPeriodo));
            });

        // Estadísticas de Infraestructura
        $totalInfraestructuras = Infraestructura::count();
        $infraestructurasOperativas = Infraestructura::where('estado', 'operativo')->count();
        $infraestructurasMantenimiento = Infraestructura::where('estado', 'mantenimiento')->count();
        $infraestructurasFueraServicio = Infraestructura::where('estado', 'fuera_de_servicio')->count();

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
        $incidenciasHoy = $incidenciasQuery->clone()->whereDate('fecha', today())->count();

        // Estadísticas por estado de incidencias
        $incidenciasPorEstado = $incidenciasQuery->clone()
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // Estadísticas por tipo de infraestructura
        $incidenciasPorTipo = $incidenciasQuery->clone()
            ->join('infraestructuras', 'incidencias.infraestructura_id', '=', 'infraestructuras.id')
            ->select('infraestructuras.tipo', DB::raw('count(*) as total'))
            ->groupBy('infraestructuras.tipo')
            ->pluck('total', 'tipo')
            ->toArray();

        // Últimas incidencias
        $ultimasIncidencias = $incidenciasQuery->clone()
            ->latest('fecha')
            ->take(5)
            ->get();

        // Últimas infraestructuras actualizadas
        $ultimasInfraestructuras = Infraestructura::latest('updated_at')
            ->take(5)
            ->get();

        // Lista de infraestructuras para el filtro
        $infraestructuras = Infraestructura::select('id', 'tipo', 'ubicacion')->get();

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
            'infraestructuras',
            'filtroEstado',
            'filtroInfraestructura',
            'filtroPeriodo'
        ));
    }
}
