<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Infrastructure;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'activeIncidents' => Incident::whereNotIn('status_id', [3])->count(),
            'totalInfrastructures' => Infrastructure::count(),
            'totalTechnicians' => User::role('Técnico')->count()
        ];

        // Datos para los gráficos
        $incidentsByStatus = Status::withCount('incidents')->get();
        $incidentsByPriority = Incident::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get();

        // Incidencias recientes
        $recentIncidents = Incident::with(['infrastructure', 'status', 'assignedTo'])
            ->latest()
            ->take(5)
            ->get();

        // Incidencias para el mapa
        $incidents = Incident::with(['infrastructure', 'status'])
            ->whereNotIn('status_id', [3])
            ->get();

        return view('dashboard', compact(
            'stats',
            'incidentsByStatus',
            'incidentsByPriority',
            'recentIncidents',
            'incidents'
        ));
    }
}
