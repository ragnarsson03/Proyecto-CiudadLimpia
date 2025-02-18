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
        $infraestructurasOperativas = Infraestructura::where('estado', 'operativo')->count();
        $infraestructurasMantenimiento = Infraestructura::where('estado', 'mantenimiento')->count();
        $infraestructurasFueraServicio = Infraestructura::where('estado', 'fuera_de_servicio')->count();

        // Infraestructuras más afectadas con detalles
        $infraestructurasMasAfectadas = DB::table('infraestructuras')
            ->select(
                'infraestructuras.id',
                'infraestructuras.tipo',
                'infraestructuras.ubicacion',
                'infraestructuras.estado',
                DB::raw('COUNT(incidencias.id) as total_incidencias'),
                DB::raw("SUM(CASE WHEN incidencias.prioridad = 'alta' THEN 1 ELSE 0 END) as incidencias_urgentes")
            )
            ->leftJoin('incidencias', 'infraestructuras.id', '=', 'incidencias.infraestructura_id')
            ->where('incidencias.fecha', '>=', now()->subDays($filtroPeriodo))
            ->groupBy('infraestructuras.id', 'infraestructuras.tipo', 'infraestructuras.ubicacion', 'infraestructuras.estado')
            ->havingRaw('COUNT(incidencias.id) > 0')
            ->orderByRaw('COUNT(incidencias.id) DESC')
            ->take(5)
            ->get();

        // Estadísticas generales de incidencias
        $totalIncidencias = $incidenciasQuery->count();
        $incidenciasHoy = $incidenciasQuery->clone()->whereDate('incidencias.fecha', today())->count();

        // Estadísticas por estado
        $incidenciasPorEstado = $incidenciasQuery->clone()
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // Asegurarse de que todos los estados posibles estén presentes
        $estadosPosibles = ['pendiente', 'en_proceso', 'resuelto', 'cancelado'];
        foreach ($estadosPosibles as $estado) {
            if (!isset($incidenciasPorEstado[$estado])) {
                $incidenciasPorEstado[$estado] = 0;
            }
        }

        // Estadísticas por tipo de infraestructura
        $incidenciasPorTipo = DB::table('incidencias')
            ->join('infraestructuras', 'incidencias.infraestructura_id', '=', 'infraestructuras.id')
            ->select(
                'infraestructuras.tipo',
                DB::raw('count(*) as total'),
                DB::raw('(count(*) * 100.0 / (select count(*) from incidencias)) as porcentaje')
            )
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
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->tipo => [
                    'total' => (int)$item->total,
                    'porcentaje' => round($item->porcentaje, 2)
                ]];
            })
            ->toArray();

        // Últimas incidencias con detalles completos
        $ultimasIncidencias = Incidencia::with(['infraestructura', 'tecnico', 'ciudadano'])
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get()
            ->map(function ($incidencia) {
                $infraestructura = $incidencia->infraestructura;
                return [
                    'id' => $incidencia->id,
                    'estado' => $incidencia->estado,
                    'descripcion' => $incidencia->descripcion,
                    'fecha' => $incidencia->fecha,
                    'prioridad' => $incidencia->prioridad,
                    'infraestructura' => $infraestructura ? [
                        'id' => $infraestructura->id,
                        'tipo' => $infraestructura->tipo,
                        'ubicacion' => $infraestructura->ubicacion,
                        'latitud' => (float)$infraestructura->latitud,
                        'longitud' => (float)$infraestructura->longitud,
                        'estado' => $infraestructura->estado
                    ] : null,
                    'tecnico' => $incidencia->tecnico ? [
                        'id' => $incidencia->tecnico->id,
                        'nombre' => $incidencia->tecnico->name,
                        'email' => $incidencia->tecnico->email
                    ] : null,
                    'ciudadano' => $incidencia->ciudadano ? [
                        'id' => $incidencia->ciudadano->id,
                        'nombre' => $incidencia->ciudadano->name,
                        'email' => $incidencia->ciudadano->email
                    ] : null
                ];
            })
            ->toArray();

        // Obtener todas las infraestructuras para el filtro
        $infraestructuras = Infraestructura::select('id', 'tipo', 'ubicacion')
            ->orderBy('tipo')
            ->orderBy('ubicacion')
            ->get();

        // Obtener incidencias por estado
        $incidenciasPorEstado = Incidencia::select('estado', DB::raw('count(*) as total'))
            ->whereNull('deleted_at')
            ->groupBy('estado')
            ->get()
            ->pluck('total', 'estado')
            ->toArray();

        // Asegurar que todos los estados estén presentes
        $estados = ['pendiente', 'en_proceso', 'resuelto', 'cancelado'];
        foreach ($estados as $estado) {
            if (!isset($incidenciasPorEstado[$estado])) {
                $incidenciasPorEstado[$estado] = 0;
            }
        }

        // Obtener incidencias por tipo de infraestructura
        $incidenciasPorTipo = Infraestructura::select('infraestructuras.tipo', DB::raw('count(incidencias.id) as total'))
            ->leftJoin('incidencias', 'infraestructuras.id', '=', 'incidencias.infraestructura_id')
            ->whereNull('infraestructuras.deleted_at')
            ->groupBy('infraestructuras.tipo')
            ->get();

        // Obtener marcadores para el mapa desde la tabla incidencias
        $marcadores = Incidencia::select(
                'incidencias.id',
                'infraestructuras.tipo as nombre',
                DB::raw('CAST(incidencias.latitud AS DECIMAL(10,8)) as latitud'),
                DB::raw('CAST(incidencias.longitud AS DECIMAL(11,8)) as longitud')
            )
            ->join('infraestructuras', 'incidencias.infraestructura_id', '=', 'infraestructuras.id')
            ->whereNotNull('incidencias.latitud')
            ->whereNotNull('incidencias.longitud')
            ->whereNull('incidencias.deleted_at')
            ->whereNull('infraestructuras.deleted_at')
            ->get();

        return view('dashboard', compact(
            'filtroEstado',
            'filtroInfraestructura',
            'filtroPeriodo',
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
            'infraestructuras',
            'marcadores'
        ));
    }
}
