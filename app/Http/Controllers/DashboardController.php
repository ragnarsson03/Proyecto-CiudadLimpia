<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\User;
use App\Models\Budget;
use App\Models\Infrastructure;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        // Estados para filtros
        $estados = [
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En Proceso',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada'
        ];

        // Tipos de infraestructura
        $tiposInfraestructura = [
            'Contenedor' => 'Contenedor de Basura',
            'Luminaria' => 'Luminaria',
            'Semáforo' => 'Semáforo',
            'Parque' => 'Parque'
        ];

        // Prioridades
        $prioridades = [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'critica' => 'Crítica'
        ];

        // Estadísticas del dashboard
        $activeIncidents = Incident::where('estado', 'pendiente')->count();
        $pendingTasks = Incident::where('estado', 'en_proceso')->count();
        $availableStaff = User::where('role', 'tecnico')
            ->whereHas('personal', function($query) {
                $query->where('disponibilidad', 'disponible');
            })
            ->count();
        $monthlyBudget = Budget::getCurrentMonthBalance();

        // Inicializar el array de incidencias por estado con valores por defecto
        $incidenciasPorEstado = [];
        foreach ($estados as $key => $value) {
            $incidenciasPorEstado[$key] = [
                'total' => 0,
                'nombre' => $value
            ];
        }

        // Obtener las estadísticas reales de incidencias por estado
        $estadisticas = Incident::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Actualizar los totales con los datos reales
        foreach ($estadisticas as $stat) {
            if (isset($incidenciasPorEstado[$stat->estado])) {
                $incidenciasPorEstado[$stat->estado]['total'] = $stat->total;
            }
        }

        // Inicializar el array de incidencias por tipo con valores por defecto
        $incidenciasPorTipo = [];
        foreach ($tiposInfraestructura as $key => $value) {
            $incidenciasPorTipo[$key] = [
                'total' => 0,
                'nombre' => $value
            ];
        }

        // Obtener las estadísticas reales de incidencias por tipo
        $estadisticasPorTipo = Incident::select('tipo', DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->get();

        // Actualizar los totales con los datos reales
        foreach ($estadisticasPorTipo as $stat) {
            if (isset($incidenciasPorTipo[$stat->tipo])) {
                $incidenciasPorTipo[$stat->tipo]['total'] = $stat->total;
            }
        }

        // Obtener incidencias con filtros
        $query = Incident::with(['technician', 'infrastructure']);

        // Aplicar filtros si existen
        if (request()->has('estado') && request('estado') !== '') {
            $query->where('estado', request('estado'));
        }

        if (request()->has('tipo_infraestructura') && request('tipo_infraestructura') !== '') {
            $query->whereHas('infrastructure', function($q) {
                $q->where('tipo', request('tipo_infraestructura'));
            });
        }

        if (request()->has('prioridad') && request('prioridad') !== '') {
            $query->where('prioridad', request('prioridad'));
        }

        // Obtener últimas incidencias
        $ultimasIncidencias = Incident::with(['technician', 'infrastructure'])
            ->latest()
            ->take(5)
            ->get();

        // Obtener incidencias recientes (para la tabla principal)
        $recentIncidents = $query->latest()->paginate(10);

        // Estadísticas adicionales
        $infrastructureStats = Infrastructure::selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->get();

        // Asegurarse de que todas las variables necesarias estén disponibles en la vista
        return view('dashboard', compact(
            'activeIncidents',
            'pendingTasks',
            'availableStaff',
            'monthlyBudget',
            'recentIncidents',
            'estados',
            'tiposInfraestructura',
            'prioridades',
            'infrastructureStats',
            'incidenciasPorEstado',
            'incidenciasPorTipo',
            'ultimasIncidencias'
        ));
    }
}
