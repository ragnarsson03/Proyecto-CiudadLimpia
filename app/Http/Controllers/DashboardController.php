<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Infraestructura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas de Infraestructura
        $totalInfraestructuras = Infraestructura::count();
        $infraestructurasOperativas = Infraestructura::where('estado', 'operativo')->count();
        $infraestructurasMantenimiento = Infraestructura::where('estado', 'mantenimiento')->count();
        $infraestructurasFueraServicio = Infraestructura::where('estado', 'fuera_de_servicio')->count();

        // Estadísticas generales de incidencias
        $totalIncidencias = Incidencia::count();
        $incidenciasHoy = Incidencia::whereDate('fecha', today())->count();

        // Estadísticas por estado de incidencias
        $incidenciasPendientes = Incidencia::where('estado', 'pendiente')->count();
        $incidenciasEnProceso = Incidencia::where('estado', 'en_proceso')->count();
        $incidenciasResueltas = Incidencia::where('estado', 'resuelto')->count();
        $incidenciasCanceladas = Incidencia::where('estado', 'cancelado')->count();

        // Calcular porcentajes
        $porcentajePendientes = $totalIncidencias > 0 ? ($incidenciasPendientes / $totalIncidencias) * 100 : 0;
        $porcentajeEnProceso = $totalIncidencias > 0 ? ($incidenciasEnProceso / $totalIncidencias) * 100 : 0;
        $porcentajeResueltas = $totalIncidencias > 0 ? ($incidenciasResueltas / $totalIncidencias) * 100 : 0;

        // Estadísticas por prioridad
        $incidenciasPrioridadBaja = Incidencia::where('prioridad', 'baja')->count();
        $incidenciasPrioridadMedia = Incidencia::where('prioridad', 'media')->count();
        $incidenciasPrioridadAlta = Incidencia::where('prioridad', 'alta')->count();
        $incidenciasPrioridadCritica = Incidencia::where('prioridad', 'critica')->count();

        // Últimas incidencias
        $ultimasIncidencias = Incidencia::with(['infraestructura', 'tecnico', 'ciudadano'])
            ->latest('fecha')
            ->take(5)
            ->get();

        // Últimas infraestructuras actualizadas
        $ultimasInfraestructuras = Infraestructura::latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalInfraestructuras',
            'infraestructurasOperativas',
            'infraestructurasMantenimiento',
            'infraestructurasFueraServicio',
            'totalIncidencias',
            'incidenciasHoy',
            'incidenciasPendientes',
            'incidenciasEnProceso',
            'incidenciasResueltas',
            'incidenciasCanceladas',
            'porcentajePendientes',
            'porcentajeEnProceso',
            'porcentajeResueltas',
            'incidenciasPrioridadBaja',
            'incidenciasPrioridadMedia',
            'incidenciasPrioridadAlta',
            'incidenciasPrioridadCritica',
            'ultimasIncidencias',
            'ultimasInfraestructuras'
        ));
    }
}
